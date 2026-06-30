<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DashboardStatsService;
use Illuminate\Console\Command;

/**
 * Recalcula e grava em cache as estatísticas do dashboard (cards + gráficos).
 * Executado pelo agendador (e no deploy) para manter os números atualizados
 * sem onerar cada carregamento do painel.
 */
class RefreshDashboardStats extends Command
{
    protected $signature = 'dashboard:refresh';

    protected $description = 'Recalcula e cacheia as estatísticas/gráficos do dashboard.';

    public function handle(DashboardStatsService $service): int
    {
        $service->refresh();
        $this->info('Estatísticas do dashboard atualizadas no cache.');

        return self::SUCCESS;
    }
}
