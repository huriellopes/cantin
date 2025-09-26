<?php

namespace App\Filament\Admin\Resources\TransPeoples\Pages;

use App\Filament\Admin\Resources\TransPeoples\TransPeopleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransPeople extends EditRecord
{
    protected static string $resource = TransPeopleResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        $this->record->load(['address']);

        $this->form->fill([
            'name' => $this->record->name,
            'phone' => $this->record->phone,
            'email' => $this->record->email,
            'address' => $this->record->address?->toArray(),
        ]);
    }
}
