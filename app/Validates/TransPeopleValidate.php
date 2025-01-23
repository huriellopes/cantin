<?php

namespace App\Validates;

class TransPeopleValidate extends Validate
{
    public array $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required|string',
        'address_id' => 'required|integer',
    ];
}
