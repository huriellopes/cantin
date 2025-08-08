<?php

namespace App\Services\BotWebhook;

use App\Contracts\BotWebhook\IBotService;
use NotificationChannels\Telegram\TelegramUpdates;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramService implements IBotService
{
    public function handle()
    {
        $response = Telegram::bot('cantinbr_bot')->getMe();

        dd($response);
    }

    private function chatTelegram()
    {
        $chatID = '';

        $updates = TelegramUpdates::create()
            ->latest()
            ->get();

        if ($updates['ok']) {
            $chatID = $updates['result'][0]['message']['chat']['id'];
        }

        return $chatID;
    }
}
