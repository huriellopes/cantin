<?php

namespace App\Archicture\Entities\Patners\Models;

use App\Archicture\Generics\Models\GenericModels;

/**
 * @property $name
 * @property $email
 * @property $phone
 * @property $path_image
 * @property $user_id
 */
class Partner extends GenericModels
{
    protected $table = "partners";

    protected $fillable = [
        'name',
        'email',
        'phone',
        'path_image',
        'user_id',
    ];
}
