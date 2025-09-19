<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Comment;
use App\Models\Terreiro;
use App\Models\Visit;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InfosWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Visualizações no Site', $this->vistCount())
                ->color('success')
                ->columnSpan(2)
                ->description('Quantidade de visitas no site')
                ->icon('heroicon-o-eye')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            Stat::make('Terreiros', $this->terreiroCount())
                ->color('success')
                ->columnSpan(2)
                ->description('Quantidade de Terreiros Cadastrados!')
                ->icon('heroicon-o-eye')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            Stat::make('Visualizações no Blog', $this->vistCount('blog'))
                ->color('success')
                ->columnSpan(2)
                ->description('Quantidade de visitas no blog')
                ->icon('heroicon-o-eye')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            Stat::make('Total de Comentários', $this->countComments())
                ->color('success')
                ->columnSpan(2)
                ->description('Quantidade de comentários')
                ->icon('heroicon-o-eye')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
        ];
    }

    private function vistCount(?string $page = null) : int
    {
        return Visit::query()
            ->when($page, fn ($queryPage) =>
                $queryPage->where('page', '=', $page))
            ->count();
    }

    private function terreiroCount() : int
    {
        return Terreiro::query()
            ->count();
    }

    private function countComments() : int
    {
        return Comment::query()
            ->whereNull('parent_id')
            ->count();
    }
}
