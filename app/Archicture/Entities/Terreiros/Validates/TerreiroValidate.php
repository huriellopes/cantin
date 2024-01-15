<?php

namespace App\Archicture\Entities\Terreiros\Validates;

use App\Archicture\Validates\Validate;

class TerreiroValidate extends Validate
{
    public array $rules = [
        'name' => 'required|string',
        'phone' => 'required|string',
        'fundationed_at' => 'required|date',
        'nation_terreiro_id' => 'required|integer',
        'leadership_orunko' => 'required|string',
        'color_of_leadership' => 'required|string',
        'address_id' => 'required|integer',
    ];
}
