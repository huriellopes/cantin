<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class RegisteredUsersChart extends ChartWidget
{
    protected ?string $heading = 'Usuários Registrados nos Últimos 30 Dias';

    protected bool $isCollapsible = true;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super-admin');
    }

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
            $labels[] = $date->format('d/m');
            $dateCounts[$dateString] = 0;
        }

        // Consulta o banco de dados para obter a contagem de usuários por dia
        $users = User::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        // Mapeia a contagem do banco para o array de datas
        foreach ($users as $user) {
            $dateCounts[$user->date] = $user->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Novos Usuários',
                    'data' => array_values($dateCounts),
                    'backgroundColor' => '#4CAF50',
                    'borderColor' => '#4CAF50',
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
