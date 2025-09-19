<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Visit;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DailyVisitsChart extends ChartWidget
{
    protected ?string $heading = 'Visitas nos Últimos 30 Dias';

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

        $visits = Visit::query()
            ->select(DB::raw('DATE(visited_at) as date'), DB::raw('count(*) as count'))
            ->where('visited_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(visited_at)'))
            ->get();

        foreach ($visits as $visit) {
            $dateCounts[$visit->date] = $visit->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total de Visitas',
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
