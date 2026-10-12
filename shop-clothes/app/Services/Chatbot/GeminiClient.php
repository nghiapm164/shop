<?php

namespace App\Services\Chatbot;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Gọi Google Gemini API (generateContent).
 * Mọi lỗi (thiếu key, hết hạn mức, mất mạng...) đều trả về null để chatbot
 * tự chuyển sang bộ phân tích offline.
 */
class GeminiClient
{
    private readonly array $config;

    public function __construct(?array $config = null)
    {
        $this->config = $config ?? config('chatbot.gemini');
    }

    public function enabled(): bool
    {
        return !empty($this->config['api_key']);
    }

    /**
     * Yêu cầu Gemini trả về JSON theo schema.
     */
    public function json(string $systemPrompt, array $contents, array $schema): ?array
    {
        $text = $this->generate($systemPrompt, $contents, [
            'temperature' => 0,
            'responseMimeType' => 'application/json',
            'responseSchema' => $schema,
        ]);

        if ($text === null) {
            return null;
        }

        $data = json_decode($text, true);

        return is_array($data) ? $data : null;
    }

    /**
     * Sinh văn bản (dùng model riêng cho việc viết lại câu trả lời nếu có cấu hình,
     * để không tranh hạn mức với bước hiểu câu hỏi).
     */
    public function text(string $systemPrompt, array $contents): ?string
    {
        return $this->generate($systemPrompt, $contents, [
            'temperature' => 0.4,
            'maxOutputTokens' => 2048,
        ], $this->config['reply_model'] ?? null);
    }

    private function generate(string $systemPrompt, array $contents, array $generationConfig, ?string $model = null): ?string
    {
        if (!$this->enabled()) {
            return null;
        }

        // Giảm thời gian "suy nghĩ" của model để chatbot phản hồi nhanh
        $model = $model ?: $this->config['model'];
        if (str_contains($model, '2.5-flash')) {
            $generationConfig['thinkingConfig'] = ['thinkingBudget' => 0];
        } elseif (str_starts_with($model, 'gemini-3') && !empty($this->config['thinking_level'])) {
            $generationConfig['thinkingConfig'] = ['thinkingLevel' => $this->config['thinking_level']];
        }

        // Câu hỏi giống hệt đã hỏi trong ngày => dùng lại kết quả, tiết kiệm hạn mức API
        $cacheKey = 'gemini:' . md5(json_encode([$model, $systemPrompt, $contents, $generationConfig]));
        if (($cached = Cache::get($cacheKey)) !== null) {
            return $cached;
        }

        try {
            $response = Http::timeout($this->config['timeout'])
                ->withHeaders(['x-goog-api-key' => $this->config['api_key']])
                ->post(rtrim($this->config['endpoint'], '/') . "/models/{$model}:generateContent", [
                    'systemInstruction' => ['parts' => [['text' => $systemPrompt]]],
                    'contents' => $contents,
                    'generationConfig' => $generationConfig,
                ]);

            if (!$response->successful()) {
                Log::warning('Gemini API error', ['status' => $response->status(), 'body' => mb_substr($response->body(), 0, 500)]);

                return null;
            }

            $parts = $response->json('candidates.0.content.parts', []);
            $text = collect($parts)
                ->reject(fn ($part) => !empty($part['thought']))
                ->pluck('text')
                ->implode('');

            if (trim($text) === '') {
                return null;
            }

            Cache::put($cacheKey, trim($text), now()->addDay());

            return trim($text);
        } catch (Throwable $e) {
            Log::warning('Gemini API exception: ' . $e->getMessage());

            return null;
        }
    }
}
