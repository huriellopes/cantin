<?php

namespace App\Filament\Admin\Resources\ParternEntityResource\Pages;

use App\Enum\Status;
use App\Filament\Admin\Resources\ParternEntityResource;
use App\Models\Address;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateParternEntity extends CreateRecord
{
    protected static string $resource = ParternEntityResource::class;

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

        $data['user_id'] = auth()->id();

        $data['status'] = Status::ACTIVE;

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
