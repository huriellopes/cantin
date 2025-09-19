<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Post;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PostsPerDayChart extends ChartWidget
{
    protected ?string $heading = 'Posts Criados nos Últimos 30 Dias';

    protected bool $isCollapsible = true;

    protected function getData(): array
    {
        $days = 30;
        $endDate = now();
        $startDate = now()->subDays($days - 1);

        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $dateCounts = [];

        foreach ($period as $date) {
            $dateString = $date->toDateString();
            $labels[] = $date->format('d/m');
            $dateCounts[$dateString] = 0;
        }

        $posts = Post::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        foreach ($posts as $post) {
            $dateCounts[$post->date] = $post->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total de Posts',
                    'data' => array_values($dateCounts),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
