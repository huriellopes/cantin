<?php

namespace App\Filament\Admin\Resources\MenuSiteResource\Pages;

use App\Filament\Admin\Resources\MenuSiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMenuSites extends ListRecords
{
    protected static string $resource = MenuSiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
