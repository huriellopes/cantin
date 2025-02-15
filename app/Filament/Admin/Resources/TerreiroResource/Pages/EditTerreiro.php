<?php

namespace App\Filament\Admin\Resources\TerreiroResource\Pages;

use App\Filament\Admin\Resources\TerreiroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTerreiro extends EditRecord
{
    protected static string $resource = TerreiroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
