<?php

namespace App\Filament\Admin\Resources\Terreiros\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Terreiros\TerreiroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerreiros extends ListRecords
{
    protected static string $resource = TerreiroResource::class;


    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
