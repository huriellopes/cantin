<?php

namespace App\Validates;

class PartnersEntitiesValidate extends Validate
{
    /**
     * @var array|string[]
     */
    public array $rules = [
        'name' => 'required|string',
        'activity_carried_out' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required|string',
        'address_id' => 'required|string',
    ];
}
