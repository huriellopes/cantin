<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Override;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * Todos os agendamentos notificam o Telegram ao concluir (sucesso/falha).
     */
    #[Override]
    protected function schedule(Schedule $schedule): void
    {
        // Regenera o sitemap.xml diariamente para refletir novos posts/páginas.
        $this->notifyTelegram($schedule->command('sitemap:generate')->dailyAt('03:00'), 'sitemap:generate');

        // Atualiza as estatísticas/gráficos do dashboard (cache) de hora em hora.
        // Por ser horário, notifica o Telegram SÓ em caso de falha (evita ruído).
        $this->notifyTelegram($schedule->command('dashboard:refresh')->hourly(), 'dashboard:refresh', notifySuccess: false);

        // Remove logs e capturas de debug antigos, mantendo os últimos 3 dias.
        $this->notifyTelegram($schedule->command('system:prune-logs --days=3')->dailyAt('04:00'), 'system:prune-logs');
    }

    /**
     * Register the commands for the application.
     */
    #[Override]
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Envia ao Telegram uma notificação de sucesso/falha do agendamento.
     * Cai em silêncio se o Telegram não estiver configurado.
     */
    private function notifyTelegram(Event $event, string $label, bool $notifySuccess = true): Event
    {
        if ($notifySuccess) {
            $event->onSuccess(function () use ($label): void {
                Log::channel('telegram_schedules')->info("✅ Agendamento concluído: {$label}");
            });
        }

        return $event->onFailure(function () use ($label): void {
            Log::channel('telegram_schedules')->error("❌ Agendamento FALHOU: {$label}");
        });
    }
}
