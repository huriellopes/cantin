<?php

declare(strict_types=1);

namespace App\Logging\Telegram;

use Monolog\Logger;

class TelegramLoggerFactory
{
    /**
     * Cria um logger Monolog que envia para o Telegram.
     *
     * @param  array{level?: string, thread?: string|null}  $config
     */
    public function __invoke(array $config): Logger
    {
        $handler = new TelegramLoggerHandler(
            token: config('services.telegram.token'),
            chatId: config('services.telegram.chat'),
            threadId: $config['thread'] ?? config('services.telegram.thread_alerts'),
            level: $config['level'] ?? 'warning',
        );

        return new Logger('telegram', [$handler]);
    }
}
