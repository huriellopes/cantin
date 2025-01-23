<?php

namespace App\Validates;

class LevelValidate extends Validate
{
    public array $rules = [
        'level' => 'required|string|min:3',
        'description' => 'required|string|min:5'
    ];
}
