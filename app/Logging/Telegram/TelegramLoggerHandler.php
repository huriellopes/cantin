<?php

declare(strict_types=1);

namespace App\Logging\Telegram;

use Illuminate\Support\Facades\Http;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Throwable;

class TelegramLoggerHandler extends AbstractProcessingHandler
{
    public function __construct(
        private readonly ?string $token,
        private readonly ?string $chatId,
        private readonly ?string $threadId,
        int|string|Level $level = Level::Warning,
        bool $bubble = true,
    ) {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        // Sem token/chat configurados, não há para onde enviar (ex.: local/CI).
        if (empty($this->token) || empty($this->chatId)) {
            return;
        }

        $data = [
            'chat_id' => $this->chatId,
            'text' => $this->format($record),
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ];

        if (!empty($this->threadId)) {
            $data['message_thread_id'] = (int) $this->threadId;
        }

        try {
            Http::timeout(5)
                ->withoutVerifying()
                ->post("https://api.telegram.org/bot{$this->token}/sendMessage", $data);
        } catch (Throwable) {
            // Falha de envio nunca pode derrubar a aplicação; já há log em arquivo.
        }
    }

    private function format(LogRecord $record): string
    {
        $emoji = $this->emojiFor($record->level);
        $env = mb_strtoupper((string) app()->environment());
        $context = $record->context;

        $lines = [];
        $lines[] = "{$emoji} <b>CaNTIn — " . $record->level->getName() . '</b>';
        $lines[] = '🌍 Ambiente: <code>' . e($env) . '</code>';
        $lines[] = '';

        if (!empty($context['endpoint'])) {
            $lines[] = '📍 Endpoint: <code>' . e((string) $context['endpoint']) . '</code>';
        }

        if (!empty($context['user'])) {
            $lines[] = '👤 Usuário: ' . e((string) $context['user']);
        }

        if (!empty($context['ip'])) {
            $lines[] = '🌐 IP: <code>' . e((string) $context['ip']) . '</code>';
        }

        if (!empty($context['exception'])) {
            $lines[] = '❌ Exceção: <code>' . e((string) $context['exception']) . '</code>';
        }

        if (!empty($context['file'])) {
            $lines[] = '📂 Arquivo: <code>' . e((string) $context['file']) . '</code>';
        }

        $lines[] = '';
        $lines[] = '💬 Mensagem:';
        $lines[] = '<pre>' . e(mb_substr($record->message, 0, 1500)) . '</pre>';
        $lines[] = '';
        $lines[] = '⏰ ' . now()->format('d/m/Y H:i:s');

        return implode("\n", $lines);
    }

    private function emojiFor(Level $level): string
    {
        return match ($level) {
            Level::Debug => '🔍',
            Level::Info => 'ℹ️',
            Level::Notice => '📝',
            Level::Warning => '⚠️',
            Level::Error => '🚨',
            Level::Critical => '🔥',
            Level::Alert => '🔔',
            Level::Emergency => '🆘',
        };
    }
}
