<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Override;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    #[Override]
    public function register(): void
    {
        $this->reportable(function (Throwable $e): void {
            //
        });
    }

    #[Override]
    public function report(Throwable $e): void
    {
        if ($this->shouldReport($e)) {
            $chatId = config('telegram.bots.cantinbrBot.chatID');

            if (app()->isLocal()) {
                $message = "🚨 **Erro na Aplicação CaNTIn Local ** 🚨\n\n";
            } else {
                $message = "🚨 **Erro na Aplicação CaNTIn Produção ** 🚨\n\n";
            }

            $message .= 'Caminho: ' . request()->fullUrl() . "\n";
            $message .= 'Mensagem: ' . $e->getMessage() . "\n";
            $message .= 'IP: ' . request()->ip() . "\n";
            $message .= 'Navegador: ' . request()->header('User-Agent') . "\n";
            $message .= 'Arquivo: ' . $e->getFile() . ' (Linha: ' . $e->getLine() . ")\n";

            try {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                ]);
            } catch (Exception $e) {
                Log::channel('telegram')->error('Erro ao enviar mensagem para o Telegram:', [
                    'message' => $message,
                    'error' => $e->getMessage(),
                ]);
            }
        }

    }

    #[Override]
    public function render($request, Exception|Throwable $e): Response|JsonResponse|HTTPResponse
    {
        return parent::render($request, $e);
    }
}
