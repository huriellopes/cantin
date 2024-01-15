<?php

namespace App\Archicture\Entities\Partners\Validates;

use App\Archicture\Validates\Validate;

class PartnersValidate extends Validate
{
    public array $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required|string',
        'path_image' => 'required|string'
    ];
}
