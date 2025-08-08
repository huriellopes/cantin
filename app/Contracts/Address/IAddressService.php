<?php

namespace App\Contracts\Address;

use App\Http\DTO\Address\AddressResultDTO;

interface IAddressService
{
    /**
     * @param string $zipcode
     * @return AddressResultDTO
     */
    public function getAddressInfoFromZipCode(string $zipcode) : AddressResultDTO;
}
