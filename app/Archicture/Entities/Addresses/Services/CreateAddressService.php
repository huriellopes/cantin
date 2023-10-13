<?php

namespace App\Archicture\Entities\Addresses\Services;

use App\Archicture\Entities\Addresses\Interface\ICreateAddressService;
use App\Archicture\Entities\Addresses\Models\Address;
use App\Archicture\Entities\Addresses\Validates\CreateAddressValidate;
use Throwable;

class CreateAddressService implements ICreateAddressService
{
    /**
     * @param object $params
     * @return Address
     * @throws Throwable
     */
    public function create(object $params): Address
    {
        $this->getValidate()->validaParametros($params);

        $address = new Address();
        $address->zipcode = $params->zipcode;
        $address->address = $params->address;
        $address->complement = $params->complement;
        $address->number = $params->number;
        $address->neighborhood = $params->neighborhood;
        $address->state_id = $params->state_id;
        $address->city_id = $params->city_id;

        $address->save();

        return $address;
    }

    /**
     * @return CreateAddressValidate
     */
    private function getValidate() : CreateAddressValidate
    {
        return new CreateAddressValidate();
    }
}
