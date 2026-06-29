<?php

declare(strict_types=1);

namespace App\Contracts\Address;

use App\Http\DTO\Address\AddressResultDTO;

interface IAddressService
{
    public function getAddressInfoFromZipCode(string $zipcode): AddressResultDTO;
}
