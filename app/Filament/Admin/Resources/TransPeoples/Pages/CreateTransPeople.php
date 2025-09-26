<?php

namespace App\Filament\Admin\Resources\TransPeoples\Pages;

use App\Filament\Admin\Resources\TransPeoples\TransPeopleResource;
use App\Models\Address;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTransPeople extends CreateRecord
{
    protected static string $resource = TransPeopleResource::class;

    protected $addressData;

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

        return $data;
    }

    protected function clearMask(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', $value);
    }
}
