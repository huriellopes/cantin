<?php

namespace App\Http\DTO\Address;

class AddressResultDTO
{
    public function __construct(
        public string $zipcode,
        public string $address,
        public string $neighborhood,
        public string $complement,
        public string $city,
        public string $state,
    ){}
}
