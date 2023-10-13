<?php

namespace App\Archicture\Entities\Users\Validates;

use App\Archicture\Validates\Validate;

class CreateUsersValidate extends Validate
{
    public array $rules = [
        'name' => 'required|string|min:4',
        'email' => 'required|string|email',
        'level_id' => 'required|integer'
    ];
}
