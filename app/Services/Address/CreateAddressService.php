<?php

namespace App\Services\Address;

use App\Models\Address;
use App\Validates\CreateAddressValidate;
use Throwable;

class CreateAddressService
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
