<?php

namespace App\Archicture\Entities\NationsTerreiros\Models;

use App\Archicture\Generics\Models\GenericModels;

/**
 * @property $nation
 * @property $description
 */
class NationsTerreiro extends GenericModels
{
    protected $table = "nations_terreiros";

    protected $fillable = [
        'nation',
        'description'
    ];
}
