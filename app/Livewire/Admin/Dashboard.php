<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Terreiro;
use App\Services\DashboardStatsService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Painel')]
class Dashboard extends Component
{
    /** Período (em dias) dos gráficos: 1 (hoje), 7, 15 ou 30. */
    public int $period = 30;

    public function setPeriod(int $period): void
    {
        $this->period = in_array($period, [1, 7, 15, 30], true) ? $period : 30;
    }

    public function render(): Factory|View
    {
        $days = in_array($this->period, [1, 7, 15, 30], true) ? $this->period : 30;

        // Lido do cache (atualizado pelo agendador `dashboard:refresh`).
        $data = resolve(DashboardStatsService::class)->get();
        $counts = $data['counts'];

        $stats = [
            ['label' => __('msg_dashboard.stat_visits'), 'value' => $counts['visits'], 'icon' => 'eye', 'color' => 'sky'],
            ['label' => __('msg_dashboard.stat_terreiros'), 'value' => $counts['terreiros'], 'icon' => 'house', 'color' => 'violet'],
            ['label' => __('msg_dashboard.stat_comments'), 'value' => $counts['comments'], 'icon' => 'message-square', 'color' => 'amber'],
            ['label' => __('msg_dashboard.stat_users'), 'value' => $counts['users'], 'icon' => 'users', 'color' => 'emerald'],
            ['label' => __('msg_dashboard.stat_partner_entities'), 'value' => $counts['partner_entities'], 'icon' => 'star', 'color' => 'rose'],
            ['label' => __('msg_dashboard.stat_trans_people'), 'value' => $counts['trans_people'], 'icon' => 'user', 'color' => 'indigo'],
        ];

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'period' => $days,
            'periodOptions' => [
                1 => __('msg_dashboard.period_today'),
                7 => __('msg_dashboard.period_7'),
                15 => __('msg_dashboard.period_15'),
                30 => __('msg_dashboard.period_30'),
            ],
            'updatedAt' => Date::parse($data['generated_at']),
            'recentTerreiros' => Terreiro::query()->latest()->take(6)->get(['id', 'name', 'created_at']),
            'charts' => [
                ['title' => __('msg_dashboard.chart_visits'), 'color' => 'sky', 'series' => $this->slice($data['series']['visits'], $days)],
                ['title' => __('msg_dashboard.chart_terreiros'), 'color' => 'violet', 'series' => $this->slice($data['series']['terreiros'], $days)],
                ['title' => __('msg_dashboard.chart_posts'), 'color' => 'amber', 'series' => $this->slice($data['series']['posts'], $days)],
            ],
        ]);
    }

    /**
     * Fatia os últimos $days pontos da série cacheada (janela de 30 dias).
     *
     * @param  array<int, array{label: string, value: int}>  $series
     * @return Collection<int, array{label: string, value: int}>
     */
    private function slice(array $series, int $days): Collection
    {
        return collect($series)->slice(-$days)->values();
    }
}
