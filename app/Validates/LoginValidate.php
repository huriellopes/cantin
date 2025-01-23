<?php

namespace App\Validates;

class LoginValidate extends Validate
{
    public array $rules = [
        'username' => 'required|string',
        'password' => 'required|string'
    ];
}
