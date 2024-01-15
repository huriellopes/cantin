<?php

namespace App\Archicture\Entities\PartnersEntities\Validades;

use App\Archicture\Validates\Validate;
use Spatie\LaravelData\Data;

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
