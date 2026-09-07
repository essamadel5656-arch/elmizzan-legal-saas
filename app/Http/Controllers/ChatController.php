<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\Setting;

class ChatController extends Controller
{
    /**
     * 1️⃣ Chat index page — shows all user sessions.
     */
    public function index()
    {
        $sessions = ChatSession::where('user_id', Auth::id() ?? 1)
            ->latest()
            ->get();

        return view('chat.index', compact('sessions'));
    }

    /**
     * 2️⃣ Show a specific session (full page load with PHP-rendered messages).
     */
    public function show($id)
    {
        $sessions = ChatSession::where('user_id', Auth::id() ?? 1)
            ->latest()
            ->get();

        $session = ChatSession::find($id);

        $messages = $session
            ? ChatMessage::where('chat_session_id', $id)
                ->orderBy('created_at', 'asc')
                ->get()
            : collect();

        return view('chat.index', compact('id', 'sessions', 'session', 'messages'));
    }

    /**
     * 3️⃣ SPA: Return messages for a session as JSON (called via AJAX).
     */
    public function getSessionMessages($id)
    {
        $session = ChatSession::where('id', $id)
            ->where('user_id', Auth::id() ?? 1)
            ->first();

        if (! $session) {
            return response()->json(['session' => null, 'messages' => []]);
        }

        $messages = ChatMessage::where('chat_session_id', $id)
            ->orderBy('created_at', 'asc')
            ->get(['id', 'role', 'message', 'file_path', 'file_name', 'created_at']);

        return response()->json([
            'session'  => ['id' => $session->id, 'title' => $session->title],
            'messages' => $messages,
        ]);
    }

    /**
     * 4️⃣ Receive a message and stream the AI reply as SSE.
     */
    public function sendMessage(Request $request, OpenAIService $openAI)
    {
        set_time_limit(240);
        $request->validate([
            'chat_session_id' => 'nullable',
            'message'         => 'nullable|string|max:10000',
            'file'            => 'nullable|file|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain,image/jpeg,image/png,image/gif,image/webp|max:10240',
            'audio'           => 'nullable|file|max:25600',
            'model'           => 'nullable|string|in:gpt-4o,gpt-4o-mini',
            'history'         => 'nullable|string',
        ]);

        // ── Module 5: AI Feature Gate ─────────────────────────────────────────
        if (! (bool)(int) Setting::get('ai_feature_enabled', '0')) {
            return response()->json([
                'error'   => 'AI feature is not enabled for this firm.',
                'message' => 'ميزة المساعد الذكي غير مفعلة. تواصل مع مسؤول النظام لتفعيلها.',
            ], 403);
        }

        $selectedModel = $request->input('model', 'gpt-4o-mini');
        $chatSessionId = $request->input('chat_session_id');
        $typedMessage  = trim($request->input('message', ''));

        // ── Auto-create a session if none provided ──────────────────────────
        if (empty($chatSessionId)) {
            $newSession    = ChatSession::create([
                'user_id' => Auth::id() ?? 1,
                'title'   => 'محادثة جديدة ' . (ChatSession::where('user_id', Auth::id() ?? 1)->count() + 1),
            ]);
            $chatSessionId = $newSession->id;
        }

        // ── Handle file uploads ──────────────────────────────────────────────
        $filePath        = null;
        $fileName        = null;
        $extractedText   = '';
        $transcribedText = '';

        if ($request->hasFile('file')) {
            $file          = $request->file('file');
            $filePath      = $file->store('chat_files', 'public');
            $fileName      = $file->getClientOriginalName();
            $extractedText = $openAI->extractTextFromFile($file);
        }

        if ($request->hasFile('audio')) {
            $audio           = $request->file('audio');
            $filePath        = $audio->store('chat_voices', 'public');
            $fileName        = 'رسالة صوتية مسجلة 🎙️';
            $transcribedText = $openAI->transcribeAudio($audio);
        }

        // ── Build the combined prompt ────────────────────────────────────────
        $parts = [];

        if (! empty($transcribedText)) {
            $parts[] = "[محتوى الرسالة الصوتية المحوَّلة]:\n{$transcribedText}";
        }

        if (! empty($extractedText)) {
            $docLabel = $fileName ?? 'مستند';
            $parts[]  = "[محتوى المستند المرفق – {$docLabel}]:\n{$extractedText}";
        }

        if (! empty($typedMessage)) {
            $parts[] = $typedMessage;
        }

        $combinedPrompt = implode("\n\n", $parts);

        if (empty($combinedPrompt)) {
            return response()->json(['error' => 'لا يوجد محتوى للإرسال'], 400);
        }

        // ── Save the user message ────────────────────────────────────────────
        ChatMessage::create([
            'chat_session_id' => $chatSessionId,
            'role'            => 'user',
            'message'         => ! empty($typedMessage) ? $typedMessage : ($transcribedText ?: null),
            'file_path'       => $filePath,
            'file_name'       => $fileName,
        ]);

        // ── Parse history from JSON string sent by client ────────────────────
        $historyRaw = $request->input('history', '[]');
        $history    = is_array($historyRaw)
            ? $historyRaw
            : (json_decode($historyRaw, true) ?? []);

        // ── Stream the SSE response ─────────────────────────────────────────
        return response()->stream(
            function () use ($openAI, $history, $combinedPrompt, $chatSessionId, $selectedModel) {

                while (ob_get_level()) {
                    ob_end_clean();
                }

                $fullBotResponse = '';

                try {
                    $apiResponse = $openAI->streamReply($history, $combinedPrompt, $selectedModel);
                    $body        = $apiResponse->getBody();
                    $buffer      = '';

                    while (! $body->eof()) {
                        $chunk   = $body->read(128);
                        $buffer .= $chunk;

                        while (($pos = strpos($buffer, "\n")) !== false) {
                            $line   = trim(substr($buffer, 0, $pos));
                            $buffer = substr($buffer, $pos + 1);

                            if (strpos($line, 'data: ') === 0) {
                                $data = substr($line, 6);

                                if ($data === '[DONE]') {
                                    break;
                                }

                                $json = json_decode($data, true);
                                if (isset($json['choices'][0]['delta']['content'])) {
                                    $text             = $json['choices'][0]['delta']['content'];
                                    $fullBotResponse .= $text;

                                    echo 'data: ' . json_encode([
                                        'text'       => $text,
                                        'session_id' => $chatSessionId,
                                        'model'      => $selectedModel,
                                    ], JSON_UNESCAPED_UNICODE) . "\n\n";

                                    if (ob_get_length()) {
                                        ob_flush();
                                    }
                                    flush();
                                }
                            }
                        }

                        if (connection_aborted()) {
                            break;
                        }
                    }

                    // ── Persist the bot's full reply ─────────────────────────
                    if (! empty($fullBotResponse)) {
                        ChatMessage::create([
                            'chat_session_id' => $chatSessionId,
                            'role'            => 'model',
                            'message'         => $fullBotResponse,
                        ]);
                    }

                } catch (\Exception $e) {
                    echo 'data: ' . json_encode(
                        ['error' => 'خطأ في الاتصال بالخادم: ' . $e->getMessage()],
                        JSON_UNESCAPED_UNICODE
                    ) . "\n\n";

                    if (ob_get_length()) {
                        ob_flush();
                    }
                    flush();
                }
            },
            200,
            [
                'Cache-Control'     => 'no-cache, no-store, must-revalidate',
                'Content-Type'      => 'text/event-stream',
                'Connection'        => 'keep-alive',
                'X-Accel-Buffering' => 'no',
            ]
        );
    }

    /**
     * 5️⃣ Create a new session manually.
     *     Returns JSON when called via AJAX, redirect otherwise.
     */
    public function createSession()
    {
        // ── Module 5: AI Feature Gate ─────────────────────────────────────────
        if (! (bool)(int) Setting::get('ai_feature_enabled', '0')) {
            return response()->json([
                'error'   => 'AI feature is not enabled for this firm.',
                'message' => 'ميزة المساعد الذكي غير مفعلة.',
            ], 403);
        }

        $session = ChatSession::create([
            'user_id' => Auth::id() ?? 1,
            'title'   => 'محادثة جديدة ' . (ChatSession::where('user_id', Auth::id() ?? 1)->count() + 1),
        ]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'session_id' => $session->id,
                'title'      => $session->title,
            ]);
        }

        return redirect()->route('chat.show', $session->id);
    }

    /**
     * 6️⃣ Delete a session and all its messages.
     *     Returns JSON when called via AJAX, redirect otherwise.
     */
    public function destroy($id)
    {
        $session = ChatSession::where('id', $id)
            ->where('user_id', Auth::id() ?? 1)
            ->first();

        if ($session) {
            ChatMessage::where('chat_session_id', $id)->delete();
            $session->delete();
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('chat.index');
    }
}