<?php

namespace App\Archicture\Entities\Cities\Validates;

use App\Archicture\Validates\Validate;

class ListCitiesValidate extends Validate
{
    public array $rules = [
        "state_id" => "nullable|integer",
    ];
}
