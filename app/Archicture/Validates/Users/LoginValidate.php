<?php

namespace App\Archicture\Validates\Users;

use App\Archicture\Validates\Validate;

class LoginValidate extends Validate
{
    public array $rules = [
        'username' => 'required|string',
        'password' => 'required|string'
    ];
}
