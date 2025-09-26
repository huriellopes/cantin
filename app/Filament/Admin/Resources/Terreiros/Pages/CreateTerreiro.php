<?php

namespace App\Filament\Admin\Resources\Terreiros\Pages;

use App\Filament\Admin\Resources\Terreiros\TerreiroResource;
use App\Models\Address;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTerreiro extends CreateRecord
{
    protected static string $resource = TerreiroResource::class;

    protected $addressData;
    protected $questionData;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['phone'] = $this->clearMask($data['phone']);

        $this->addressData = $data['address'];
        unset($data['address']);

        $this->addressData['zipcode'] = $this->clearMask($this->addressData['zipcode']);

        $address = Address::query()
            ->where('zipcode', '=', $this->addressData['zipcode'])
            ->first();

        if (!$address) {
            $address = Address::create($this->addressData);
        }

        $data['address_id'] = $address->id;

        $this->questionData = $data['question'];
        unset($data['question']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = parent::handleRecordCreation($data);

        if (isset($this->questionData)) {
            $this->questionData['terreiro_id'] = $record->id;

            $record->question()->create($this->questionData);
        }

        return $record;
    }

    protected function clearMask(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', $value);
    }
}
