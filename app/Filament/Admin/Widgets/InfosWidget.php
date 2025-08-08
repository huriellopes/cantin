<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Terreiro;
use App\Models\Visit;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InfosWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '5s';

    protected function getStats(): array
    {
        $visitCount = Visit::query()->count();
        $countTotal = Terreiro::query()->count();

        return [
            Stat::make('Visitas', $visitCount)
                ->color('success')
                ->description('Quantidade de visitas no site')
                ->icon('heroicon-o-eye')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            Stat::make('Terreiros', $countTotal)
                ->color('success')
                ->description('Quantidade de Terreiros Cadastrados!')
                ->icon('heroicon-o-eye')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
        ];
    }
}
