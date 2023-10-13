<?php

namespace App\Archicture\Entities\Addresses\Validates;

use App\Archicture\Validates\Validate;

class CreateAddressValidate extends Validate
{
    public array $rules = [
        'zipcode' => 'required|string|min:7',
        'address' => 'required|string',
        'complement' => 'nullable|string',
        'number' => 'required|integer',
        'neighborhood' => 'required|string',
        'state_id' => 'required|integer',
        'city_id' => 'required|integer',
    ];
}
