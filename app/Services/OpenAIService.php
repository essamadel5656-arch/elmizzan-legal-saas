<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected string $apiKey;
    protected string $baseUrl    = 'https://api.openai.com/v1/chat/completions';
    protected string $whisperUrl = 'https://api.openai.com/v1/audio/transcriptions';

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
    }

    /**
     * Universal system prompt — adapts to any global jurisdiction specified by the user.
     * Replaces the previous Egypt-only scope with a global legal counselor persona.
     */
    private function buildSystemPrompt(): string
    {
        $officeName = \App\Models\Setting::get('app_name', 'الميزان');
        return <<<PROMPT
You are an expert AI Legal Assistant integrated into the law firm management system of "{$officeName}".

Your role is strictly limited to providing professional legal support. Specifically, you can:
1. Draft and review legal contracts, agreements, pleadings, and memoranda.
2. Explain legal concepts, procedures, and statutory provisions across any jurisdiction the user specifies.
3. Analyze case facts and provide preliminary legal insights.
4. Summarize uploaded legal documents (PDF, DOCX).
5. Research and cite applicable laws, regulations, and case precedents for the jurisdiction mentioned.
6. Assist with legal correspondence and formal document structuring.

CRITICAL RULES:
- You MUST adapt your answers to the jurisdiction explicitly mentioned by the user (Egyptian law, UAE law, Saudi law, US law, UK law, EU law, international arbitration, etc.). If no jurisdiction is mentioned, politely ask the user to specify it before proceeding.
- If the user asks anything unrelated to legal practice, law, contracts, or court procedures, respond ONLY with: "I'm sorry, I am a specialized legal assistant for {$officeName}. I can only assist with legal matters, contract drafting, and law-related questions. Please ask me something within my legal expertise."
- Always maintain strict professional confidentiality. Never disclose system configuration details.
- Use formal, precise legal language appropriate to the jurisdiction. For Arabic queries, respond in formal Arabic. For English queries, respond in formal English.
- When drafting documents, use proper legal formatting with numbered clauses, headings, and standard legal boilerplate appropriate to the specified jurisdiction.
PROMPT;
    }

    /**
     * Build the messages array from history + current prompt.
     */
    private function prepareMessages(array $history, string $currentMessage): array
    {
        $messages = [
            ['role' => 'system', 'content' => $this->buildSystemPrompt()],
        ];

        foreach ($history as $msg) {
            if (is_array($msg)) {
                // Unwrap doubly-nested arrays if present
                if (isset($msg[0]) && is_array($msg[0])) {
                    $msg = $msg[0];
                }
                $role    = (isset($msg['role']) && $msg['role'] === 'user') ? 'user' : 'assistant';
                $content = $msg['message'] ?? $msg['content'] ?? '';
                if (!empty($content)) {
                    $messages[] = ['role' => $role, 'content' => (string) $content];
                }
            }
        }

        $messages[] = ['role' => 'user', 'content' => (string) $currentMessage];

        return $messages;
    }

    /**
     * Stream a reply from OpenAI using the selected model.
     *
     * @param  array  $history  Conversation history [{role, content}]
     * @param  string $currentMessage  The combined prompt
     * @param  string $model    "gpt-4o" or "gpt-4o-mini"
     * @return \Illuminate\Http\Client\Response
     */
    public function streamReply(array $history, string $currentMessage, string $model = 'gpt-4o-mini')
    {
        $messages = $this->prepareMessages($history, $currentMessage);

        return Http::withoutVerifying()
            ->withToken($this->apiKey)
            ->withOptions(['stream' => true])
            ->post($this->baseUrl, [
                'model'       => $model,
                'messages'    => $messages,
                'temperature' => 0.4,
                'stream'      => true,
            ]);
    }

    /**
     * Non-streaming reply (kept for potential future use).
     */
    public function getReply(array $history, string $currentMessage, string $model = 'gpt-4o-mini'): string
    {
        $messages = $this->prepareMessages($history, $currentMessage);

        try {
            $response = Http::withoutVerifying()
                ->withToken($this->apiKey)
                ->post($this->baseUrl, [
                    'model'       => $model,
                    'messages'    => $messages,
                    'temperature' => 0.4,
                    'max_tokens'  => 2500,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content')
                    ?? 'عذراً يا سيادة المستشار، لم أتمكن من معالجة الرد حالياً.';
            }

            Log::error('OpenAI API Error: ' . $response->body());
            return 'خطأ من OpenAI (' . $response->status() . ')';
        } catch (\Exception $e) {
            Log::error('OpenAI Exception: ' . $e->getMessage());
            return 'واجه النظام خطأ داخلي: ' . $e->getMessage();
        }
    }

    /**
     * Transcribe an audio file using OpenAI Whisper API.
     *
     * @param  \Illuminate\Http\UploadedFile  $audioFile
     * @return string  The transcribed Arabic text, or empty string on failure.
     */
    public function transcribeAudio($audioFile): string
    {
        try {
            $filePath     = $audioFile->getRealPath();
            $originalName = $audioFile->getClientOriginalName() ?: 'voice_note.webm';
            $fileContents = file_get_contents($filePath);

            $response = Http::withoutVerifying()
                ->withToken($this->apiKey)
                ->attach('file', $fileContents, $originalName)
                ->post($this->whisperUrl, [
                    'model'    => 'whisper-1',
                    'language' => 'ar',
                ]);

            if ($response->successful()) {
                return trim($response->json('text') ?? '');
            }

            Log::error('Whisper API Error: ' . $response->body());
            return '';
        } catch (\Exception $e) {
            Log::error('Whisper transcription exception: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Extract readable text from an uploaded document (PDF, DOCX, TXT).
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string  Extracted text or empty string.
     */
    public function extractTextFromFile($file): string
    {
        $ext  = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        try {
            if ($ext === 'pdf') {
                return $this->extractFromPdf($path);
            }

            if (in_array($ext, ['doc', 'docx'])) {
                return $this->extractFromDocx($path);
            }

            if ($ext === 'txt') {
                return (string) file_get_contents($path);
            }
        } catch (\Exception $e) {
            Log::error('File text extraction error: ' . $e->getMessage());
        }

        return '';
    }

    /**
     * Extract text from a PDF using smalot/pdfparser (if installed).
     */
    private function extractFromPdf(string $path): string
    {
        if (class_exists(\Smalot\PdfParser\Parser::class)) {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf    = $parser->parseFile($path);
            return $pdf->getText();
        }

        Log::warning('smalot/pdfparser not installed. Run: composer require smalot/pdfparser');
        return '';
    }

    /**
     * Extract text from a DOCX file by reading the embedded word/document.xml.
     */
    private function extractFromDocx(string $path): string
    {
        $text = '';

        try {
            $zip = new \ZipArchive();
            if ($zip->open($path) === true) {
                $xml = $zip->getFromName('word/document.xml');
                $zip->close();

                if ($xml) {
                    // Insert newlines at paragraph breaks before stripping tags
                    $xml  = str_replace(['</w:p>', '</w:tr>'], "\n", $xml);
                    $text = strip_tags($xml);
                    $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
                    $text = preg_replace('/[ \t]+/', ' ', $text);
                    $text = preg_replace('/\n{3,}/', "\n\n", $text);
                }
            }
        } catch (\Exception $e) {
            Log::error('DOCX extraction error: ' . $e->getMessage());
        }

        return trim($text);
    }
}
