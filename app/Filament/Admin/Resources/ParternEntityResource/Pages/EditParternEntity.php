<?php

namespace App\Filament\Admin\Resources\ParternEntityResource\Pages;

use App\Filament\Admin\Resources\ParternEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParternEntity extends EditRecord
{
    protected static string $resource = ParternEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
