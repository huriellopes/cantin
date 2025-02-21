<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Visit;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitsTotalWidget extends BaseWidget
{
    protected ?string $heading = 'Visitas';

    protected static ?string $pollingInterval = '5s';

    protected function getStats(): array
    {
        $visitCount = Visit::query()->count();

        return [
            Stat::make(__('Total'), $visitCount)
                ->color('success')
                ->description('Quantidade de visitas no site')
                ->icon('heroicon-o-eye')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
        ];
    }
}
