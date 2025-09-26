<?php

namespace App\Filament\Admin\Resources\ParternEntities\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\ParternEntities\ParternEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParternEntities extends ListRecords
{
    protected static string $resource = ParternEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
