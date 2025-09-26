<?php

namespace App\Filament\Admin\Resources\TransPeoples\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\TransPeoples\TransPeopleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransPeople extends ListRecords
{
    protected static string $resource = TransPeopleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
