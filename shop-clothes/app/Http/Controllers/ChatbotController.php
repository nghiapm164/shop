<?php

namespace App\Http\Controllers;

use App\Services\Chatbot\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Nhận tin nhắn của khách và trả về câu trả lời + sản phẩm gợi ý (JSON).
     * Lịch sử hội thoại và tiêu chí tìm kiếm gần nhất được lưu trong session
     * để hiểu các câu hỏi nối tiếp.
     */
    public function message(Request $request, ChatbotService $chatbot): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $message = trim($data['message']);
        $history = $request->session()->get('chatbot.history', []);
        $previous = $request->session()->get('chatbot.context');

        $result = $chatbot->handle($message, $history, $previous);

        $history[] = ['role' => 'user', 'text' => $message];
        $history[] = ['role' => 'model', 'text' => $result['reply']];
        $request->session()->put('chatbot.history', array_slice($history, -20));

        if ($result['context']) {
            $request->session()->put('chatbot.context', $result['context']);
        }

        unset($result['context']);

        return response()->json($result);
    }

    public function reset(Request $request): JsonResponse
    {
        $request->session()->forget(['chatbot.history', 'chatbot.context']);

        return response()->json(['ok' => true, 'suggestions' => ChatbotService::suggestions()]);
    }
}
