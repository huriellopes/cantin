<?php

namespace App\Validates;

class LogValidate extends Validate
{
    public array $rules = [
        'action' => 'required|string',
        'type' => 'required|string',
        'content' => 'required|string',
    ];
}
