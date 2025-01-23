<?php

namespace App\Validates;

class GetCepValidate extends Validate
{
    public array $rules = [
        'zipcode' => 'required|string|min:7'
    ];
}
