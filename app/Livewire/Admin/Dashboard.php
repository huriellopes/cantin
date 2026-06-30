<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Enum\Status;
use App\Models\Comment;
use App\Models\PartnerEntity;
use App\Models\Post;
use App\Models\Terreiro;
use App\Models\TransPeople;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Painel')]
class Dashboard extends Component
{
    public function render(): Factory|View
    {
        $stats = [
            ['label' => __('msg_dashboard.stat_visits'), 'value' => Visit::query()->count(), 'icon' => 'eye', 'color' => 'sky'],
            ['label' => __('msg_dashboard.stat_terreiros'), 'value' => Terreiro::query()->count(), 'icon' => 'house', 'color' => 'violet'],
            ['label' => __('msg_dashboard.stat_comments'), 'value' => Comment::query()->whereNull('parent_id')->count(), 'icon' => 'message-square', 'color' => 'amber'],
            ['label' => __('msg_dashboard.stat_users'), 'value' => User::query()->count(), 'icon' => 'users', 'color' => 'emerald'],
            ['label' => __('msg_dashboard.stat_partner_entities'), 'value' => PartnerEntity::query()->where('status', Status::ACTIVE)->count(), 'icon' => 'star', 'color' => 'rose'],
            ['label' => __('msg_dashboard.stat_trans_people'), 'value' => TransPeople::query()->count(), 'icon' => 'user', 'color' => 'indigo'],
        ];

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'recentTerreiros' => Terreiro::query()->latest()->take(6)->get(['id', 'name', 'created_at']),
            'charts' => [
                ['title' => __('msg_dashboard.chart_visits'), 'color' => 'sky', 'series' => $this->dailySeries(Visit::query(), 'visited_at')],
                ['title' => __('msg_dashboard.chart_terreiros'), 'color' => 'violet', 'series' => $this->dailySeries(Terreiro::query(), 'created_at')],
                ['title' => __('msg_dashboard.chart_posts'), 'color' => 'amber', 'series' => $this->dailySeries(Post::query(), 'created_at')],
            ],
        ]);
    }

    /**
     * Série diária dos últimos 30 dias (preenchendo dias sem dados com zero).
     *
     * @return Collection<int, array{label: string, value: int}>
     */
    private function dailySeries(Builder $query, string $column): Collection
    {
        $start = now()->subDays(29)->startOfDay();

        $counts = $query
            ->where($column, '>=', $start)
            ->selectRaw("DATE({$column}) as d, COUNT(*) as c")
            ->groupBy('d')
            ->pluck('c', 'd');

        return collect(range(29, 0))->map(function (int $daysAgo) use ($counts): array {
            $date = Date::now()->subDays($daysAgo);

            return [
                'label' => $date->format('d/m'),
                'value' => (int) ($counts[$date->format('Y-m-d')] ?? 0),
            ];
        });
    }
}
