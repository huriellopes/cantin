<?php

namespace App\Archicture\Entities\Addresses\Interface;

use App\Archicture\Entities\Addresses\Models\Address;

interface ICreateAddressService
{
    /**
     * @param object $params
     * @return Address
     */
    public function create(object $params) : Address;
}
