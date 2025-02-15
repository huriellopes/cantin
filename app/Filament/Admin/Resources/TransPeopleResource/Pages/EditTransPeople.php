<?php

namespace App\Filament\Admin\Resources\TransPeopleResource\Pages;

use App\Filament\Admin\Resources\TransPeopleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransPeople extends EditRecord
{
    protected static string $resource = TransPeopleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
