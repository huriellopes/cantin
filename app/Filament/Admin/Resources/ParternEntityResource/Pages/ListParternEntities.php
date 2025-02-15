<?php

namespace App\Filament\Admin\Resources\ParternEntityResource\Pages;

use App\Filament\Admin\Resources\ParternEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParternEntities extends ListRecords
{
    protected static string $resource = ParternEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
