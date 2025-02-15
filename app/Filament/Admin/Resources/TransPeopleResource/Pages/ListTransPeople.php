<?php

namespace App\Filament\Admin\Resources\TransPeopleResource\Pages;

use App\Filament\Admin\Resources\TransPeopleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransPeople extends ListRecords
{
    protected static string $resource = TransPeopleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
