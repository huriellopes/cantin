<?php

declare(strict_types=1);

namespace App\Services\BotWebhook;

use App\Contracts\BotWebhook\IBotService;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramService implements IBotService
{
    public function handle(): never
    {
        $response = Telegram::bot('cantinbr_bot')->getMe();

        dd($response);
    }
}
