<?php

namespace App\Archicture\Entities\Peoples\Models;

use App\Archicture\Generics\Models\GenericModels;

/**
 * @property $name
 * @property $email
 * @property $phone
 * @property $birth
 * @property $sex
 * @property $description_sex
 * @property $address_id
 * @property $type_people_id
 */
class People extends GenericModels
{
    protected $table = "peoples";

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth',
        'sex',
        'description_sex',
        'address_id',
        'type_people_id',
    ];
}
