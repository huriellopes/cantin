<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Comment;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopLevelCommentsChart extends ChartWidget
{
    protected ?string $heading = 'Comentários Principais nos Últimos 30 Dias';

    protected bool $isCollapsible = true;

    protected function getData(): array
    {
        // Define o intervalo de tempo para os últimos 30 dias
        $days = 30;
        $endDate = now();
        $startDate = now()->subDays($days - 1);

        // Gera um array com todas as datas do período para garantir que dias sem posts sejam exibidos
        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $dateCounts = [];

        foreach ($period as $date) {
            $dateString = $date->toDateString();
            $labels[] = $date->format('d/m'); // Formato do label: "01/01"
            $dateCounts[$dateString] = 0; // Inicializa a contagem para cada dia como 0
        }

        // Consulta o banco de dados para obter a contagem de comentários por dia
        $comments = Comment::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereNull('parent_id') // A condição chave para filtrar apenas os comentários principais
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        // Mapeia a contagem do banco para o array de datas
        foreach ($comments as $comment) {
            $dateCounts[$comment->date] = $comment->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total de Comentários',
                    'data' => array_values($dateCounts),
                    'backgroundColor' => '#4BC0C0',
                    'borderColor' => '#4BC0C0',
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
