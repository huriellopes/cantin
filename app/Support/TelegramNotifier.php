<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Throwable;

class TelegramNotifier
{
    /**
     * Envia uma mensagem ao tópico de alertas do Telegram (best-effort).
     */
    public static function send(string $html): void
    {
        $token = config('services.telegram.token');
        $chat = config('services.telegram.chat');

        if (empty($token) || empty($chat)) {
            return;
        }

        $data = [
            'chat_id' => $chat,
            'text' => $html,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ];

        $thread = config('services.telegram.thread_alerts');

        if (!empty($thread)) {
            $data['message_thread_id'] = (int) $thread;
        }

        try {
            Http::timeout(5)->withoutVerifying()
                ->post("https://api.telegram.org/bot{$token}/sendMessage", $data);
        } catch (Throwable) {
            // best-effort
        }
    }
}
