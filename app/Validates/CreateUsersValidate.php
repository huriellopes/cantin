<?php

namespace App\Validates;

class CreateUsersValidate extends Validate
{
    public array $rules = [
        'name' => 'required|string|min:4',
        'email' => 'required|string|email',
        'level_id' => 'required|integer'
    ];
}
