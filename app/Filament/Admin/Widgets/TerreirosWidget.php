<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Terreiro;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TerreirosWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $countTotal = Terreiro::query()->count();

        return [
            Stat::make(__('Total'), $countTotal)
                ->color('success')
                ->description('Quantidade de Terreiros Cadastrados!')
                ->icon('heroicon-o-eye')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
        ];
    }
}
