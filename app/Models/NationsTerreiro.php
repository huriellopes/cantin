<?php

namespace App\Models;

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
