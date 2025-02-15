<?php

namespace App\Filament\Admin\Resources\NationTerreiroResource\Pages;

use App\Filament\Admin\Resources\NationTerreiroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNationTerreiro extends EditRecord
{
    protected static string $resource = NationTerreiroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
