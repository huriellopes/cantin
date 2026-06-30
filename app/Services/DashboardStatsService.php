<?php

declare(strict_types=1);

namespace App\Services;

use App\Enum\Status;
use App\Models\Comment;
use App\Models\PartnerEntity;
use App\Models\Post;
use App\Models\Terreiro;
use App\Models\TransPeople;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

/**
 * Calcula e mantém em cache as estatísticas do dashboard (cards + séries dos
 * gráficos dos últimos 30 dias). Os números são pesados (GROUP BY sobre a
 * tabela de visitas, que cresce), então são recalculados por um comando
 * agendado (dashboard:refresh) e apenas lidos do cache no render.
 */
class DashboardStatsService
{
    public const string CACHE_KEY = 'dashboard:stats';

    /** Janela máxima (dias) mantida em cache; o filtro do painel fatia daqui. */
    public const int WINDOW_DAYS = 30;

    /**
     * Recalcula e grava no cache. Chamado pelo schedule e no deploy.
     */
    public function refresh(): void
    {
        Cache::put(self::CACHE_KEY, $this->compute(), now()->addDay());
    }

    /**
     * Lê do cache; se vazio (cold start), calcula e grava.
     *
     * @return array{generated_at: string, counts: array<string, int>, series: array<string, array<int, array{label: string, value: int}>>}
     */
    public function get(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addDay(), fn (): array => $this->compute());
    }

    /**
     * @return array{generated_at: string, counts: array<string, int>, series: array<string, array<int, array{label: string, value: int}>>}
     */
    private function compute(): array
    {
        return [
            'generated_at' => now()->toIso8601String(),
            'counts' => [
                'visits' => Visit::query()->count(),
                'terreiros' => Terreiro::query()->count(),
                'comments' => Comment::query()->whereNull('parent_id')->count(),
                'users' => User::query()->count(),
                'partner_entities' => PartnerEntity::query()->where('status', Status::ACTIVE)->count(),
                'trans_people' => TransPeople::query()->count(),
            ],
            'series' => [
                'visits' => $this->dailySeries(Visit::query(), 'visited_at'),
                'terreiros' => $this->dailySeries(Terreiro::query(), 'created_at'),
                'posts' => $this->dailySeries(Post::query(), 'created_at'),
            ],
        ];
    }

    /**
     * Série diária dos últimos WINDOW_DAYS dias (dias sem dados = 0).
     *
     * @return array<int, array{label: string, value: int}>
     */
    private function dailySeries(Builder $query, string $column): array
    {
        $start = now()->subDays(self::WINDOW_DAYS - 1)->startOfDay();

        $counts = $query
            ->where($column, '>=', $start)
            ->selectRaw("DATE({$column}) as d, COUNT(*) as c")
            ->groupBy('d')
            ->pluck('c', 'd');

        return collect(range(self::WINDOW_DAYS - 1, 0))->map(function (int $daysAgo) use ($counts): array {
            $date = Date::now()->subDays($daysAgo);

            return [
                'label' => $date->format('d/m'),
                'value' => (int) ($counts[$date->format('Y-m-d')] ?? 0),
            ];
        })->all();
    }
}
