<?php

namespace App\Models;

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
