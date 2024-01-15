<?php

namespace App\Archicture\Entities\Logs\Validates;

use App\Archicture\Validates\Validate;

class LogValidate extends Validate
{
    public array $rules = [
        'action' => 'required|string',
        'type' => 'required|string',
        'content' => 'required|string',
    ];
}
