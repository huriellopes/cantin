<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Terreiro;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TerreirosPerDayChart extends ChartWidget
{
    protected ?string $heading = 'Terreiros Cadastrados nos Últimos 30 Dias';

    protected int | string | array $columnSpan = 'full';

    protected bool $isCollapsible = true;

    protected function getData(): array
    {
        // Define o intervalo de tempo para os últimos 30 dias
        $days = 30;
        $endDate = now();
        $startDate = now()->subDays($days - 1);

        // Gera um array com todas as datas do período
        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $dateCounts = [];

        foreach ($period as $date) {
            $dateString = $date->toDateString();
            $labels[] = $date->format('d/m'); // Formato do label: "01/01"
            $dateCounts[$dateString] = 0; // Inicializa a contagem para cada dia como 0
        }

        // Consulta o banco de dados para obter a contagem de terreiros por dia
        $terreiros = Terreiro::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        // Mapeia a contagem do banco para o array de datas
        foreach ($terreiros as $terreiro) {
            $dateCounts[$terreiro->date] = $terreiro->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Novos Terreiros',
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
