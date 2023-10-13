<?php

namespace App\Archicture\Entities\CEP\Validate;

use App\Archicture\Validates\Validate;

class GetCepValidate extends Validate
{
    public array $rules = [
        'zipcode' => 'required|string|min:7'
    ];
}
