<?php

namespace App\Archicture\Entities\TypeTerreiros\Models;

use App\Archicture\Generics\Models\GenericModels;

/**
 * @property $description
 */
class TypeTerreiro extends GenericModels
{
    protected $table = "type_terreiros";

    protected $fillable = [
        "description"
    ];
}
