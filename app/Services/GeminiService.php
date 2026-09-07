<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        // قراءة مفتاح الـ API من ملف الإعدادات لضمان الاستقرار
        $this->apiKey = config('services.openai.key');
        $this->baseUrl = 'https://api.openai.com/v1/chat/completions';
        $this->model = 'gpt-4o-mini'; // الموديل السريع والذكي والمناسب للمشروع
    }

    /**
     * دالة معالجة النصوص البرومبت الموحدة لمنع الإجابات العامة (مثل صينية البطاطس)
     * وتنسيق المصفوفة بشكل صحيح ومسطح يمنع خطأ الـ 400 تماماً.
     */
    private function prepareMessages(array $history, string $currentMessage): array
    {
        // البرومبت الصارم جداً لرفض أي أسئلة خارج القانون المصري
        $systemPrompt = "أنت مساعد قانوني ذكي وصارم جداً مخصص لمكتب محاماة في مصر. وظيفتك الحصرية والوحيدة هي مساعدة المحامي في إدارة مكتبه، صياغة العقود، ومراجعة القضايا طبقاً للقوانين المصرية. إذا سألك المستخدم عن أي موضوع خارج سياق القانون المصري تماماً (مثل الطبخ، الوصفات، صينية البطاطس، الرياضة، أو الأسئلة العامة)، يجب أن تعتذر منه فوراً وبأدب شديد، وتخبره بعبارة واضحة: 'عذراً يا سيادة المستشار، أنا مساعد قانوني مخصص لدعمك في أعمال المحاماة والقانون المصري فقط، ولا يمكنني الإجابة على أسئلة خارج هذا النطاق'.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // تنظيف الهيستوري وفك أي مصفوفات متداخلة لتفادي أخطاء الـ Object/Array
        foreach ($history as $msg) {
            if (is_array($msg)) {
                // إذا كانت الرسالة مغلّفة بالخطأ داخل مصفوفة فرعية أخرى، نقوم بفكها
                if (isset($msg[0]) && is_array($msg[0])) {
                    $msg = $msg[0];
                }

                $role = (isset($msg['role']) && $msg['role'] === 'user') ? 'user' : 'assistant';
                $content = $msg['message'] ?? $msg['content'] ?? '';

                if (!empty($content)) {
                    $messages[] = [
                        'role' => $role,
                        'content' => (string)$content
                    ];
                }
            }
        }

        // إضافة رسالة المستخدم الحالية في نهاية المصفوفة كـ Object صريح
        $messages[] = [
            'role' => 'user',
            'content' => (string)$currentMessage
        ];

        return $messages;
    }

    /**
     * الطريقة الأولى: الرد التقليدي (يأتي النص كاملاً دفعة واحدة)
     */
    public function getReply(array $history, string $currentMessage)
    {
        $messages = $this->prepareMessages($history, $currentMessage);

        try {
            $response = Http::withoutVerifying()
                ->withToken($this->apiKey)
                ->post($this->baseUrl, [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => 0.4,
                    'max_tokens' => 2500
                ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['choices'][0]['message']['content'] ?? 'عذراً يا سيادة المستشار، لم أتمكن من معالجة الرد حالياً.';
            }

            Log::error('OpenAI API Error: ' . $response->body());
            return 'خطأ من OpenAI (' . $response->status() . '): ' . ($response->json()['error']['message'] ?? 'مشكلة غير معروفة');

        } catch (\Exception $e) {
            Log::error('OpenAI Exception: ' . $e->getMessage());
            return 'واجه النظام خطأ داخلي: ' . $e->getMessage();
        }
    }

    /**
     * الطريقة الثانية: الرد المتدفق (Streaming - يظهر كلمة كلمة بشكل احترافي)
     */
    public function streamReply(array $history, string $currentMessage)
    {
        $messages = $this->prepareMessages($history, $currentMessage);

        return Http::withoutVerifying()
            ->withToken($this->apiKey)
            ->withOptions(['stream' => true]) 
            ->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.4, 
                'stream' => true, 
            ]);
    }
}