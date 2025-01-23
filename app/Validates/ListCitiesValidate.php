<?php

namespace App\Validates;

class ListCitiesValidate extends Validate
{
    public array $rules = [
        "state_id" => "nullable|integer",
        'state' => 'nullable|string',
    ];
}
