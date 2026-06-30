<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Override;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        if (!$this->shouldReport($e)) {
            return;
        }

        $context = $this->contextFor($e);

        // Histórico sempre persistido em arquivo.
        Log::channel('daily')->error($e->getMessage(), $context);

        // Ruído de scanners/crawlers (404 em caminhos típicos de varredura) não alerta.
        if ($this->isScannerNoise($e)) {
            return;
        }

        // Throttle: no máximo 1 alerta por assinatura de erro a cada 5 minutos.
        $signature = 'tg-alert:' . md5($e::class . $e->getFile() . $e->getLine());

        if (!Cache::add($signature, true, now()->addMinutes(5))) {
            return;
        }

        try {
            Log::channel('telegram_alerts')->error($e->getMessage(), $context);
        } catch (Throwable $ex) {
            Log::channel('telegram')->error('Falha ao enviar alerta ao Telegram', [
                'error' => $ex->getMessage(),
            ]);
        }
    }

    #[Override]
    public function render($request, Exception|Throwable $e): Response|JsonResponse|HTTPResponse
    {
        return parent::render($request, $e);
    }

    /**
     * @return array<string, string>
     */
    private function contextFor(Throwable $e): array
    {
        $user = auth()->user();

        return [
            'endpoint' => request()->method() . ' ' . request()->fullUrl(),
            'user' => $user ? "#{$user->id} - {$user->name}" : 'visitante',
            'ip' => (string) request()->ip(),
            'exception' => $e::class,
            'file' => basename($e->getFile()) . ':' . $e->getLine(),
        ];
    }

    private function isScannerNoise(Throwable $e): bool
    {
        if (!$e instanceof NotFoundHttpException) {
            return false;
        }

        $path = mb_strtolower(request()->path());

        foreach (['wp-', '.env', '.git', 'phpmyadmin', 'xmlrpc', 'vendor/', '.php', 'wp/'] as $needle) {
            if (str_contains($path, $needle)) {
                return true;
            }
        }

        return false;
    }
}
