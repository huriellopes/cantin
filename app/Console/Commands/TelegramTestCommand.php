<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TelegramTestCommand extends Command
{
    protected $signature = 'telegram:test {message? : Mensagem de teste}';

    protected $description = 'Envia uma mensagem de teste ao tópico de alertas do Telegram';

    public function handle(): int
    {
        if (empty(config('services.telegram.token')) || empty(config('services.telegram.chat'))) {
            $this->error('Configure TELEGRAM_BOT_TOKEN e TELEGRAM_CHAT_ID no .env.');

            return self::FAILURE;
        }

        $message = (string) ($this->argument('message') ?? 'Teste de alerta do CaNTIn ✅');

        Log::channel('telegram_alerts')->error($message, [
            'endpoint' => 'artisan telegram:test',
            'exception' => 'Teste manual',
        ]);

        $this->info('Mensagem enviada ao Telegram (tópico de alertas).');

        return self::SUCCESS;
    }
}
