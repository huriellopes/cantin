<?php

namespace App\Filament\Admin\Resources\TypeTerreiroResource\Pages;

use App\Filament\Admin\Resources\TypeTerreiroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypeTerreiro extends EditRecord
{
    protected static string $resource = TypeTerreiroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
