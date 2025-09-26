<?php

namespace App\Filament\Admin\Resources\ParternEntities\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\ParternEntities\ParternEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParternEntity extends EditRecord
{
    protected static string $resource = ParternEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
