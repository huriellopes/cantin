<?php

namespace App\Filament\Admin\Resources\TypePeopleResource\Pages;

use App\Filament\Admin\Resources\TypePeopleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypePeople extends EditRecord
{
    protected static string $resource = TypePeopleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
