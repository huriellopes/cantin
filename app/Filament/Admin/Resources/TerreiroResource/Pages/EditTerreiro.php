<?php

namespace App\Filament\Admin\Resources\TerreiroResource\Pages;

use App\Filament\Admin\Resources\TerreiroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTerreiro extends EditRecord
{
    protected static string $resource = TerreiroResource::class;

    protected $addressData;
    protected $questionData;

    public function mount($record): void
    {
        parent::mount($record);

        $this->record->load(['address', 'question']);

        $this->form->fill([
            'name' => $this->record->name,
            'phone' => $this->record->phone,
            'email' => $this->record->email,
            'nation_terreiro_id' => $this->record->nation_terreiro_id,
            'leadership_orunko' => $this->record->leadership_orunko,
            'color_of_leadership' => $this->record->color_of_leadership,
            'address' => $this->record->address?->toArray(),
            'question' => $this->record->question?->toArray(),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->addressData = $data['address'];
        $this->questionData = $data['question'];

        unset($data['address']);
        unset($data['question']);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        $record->address->update($this->addressData);

        $record->question()->firstOrCreate()->update($this->questionData);

        return $record;
    }
}
