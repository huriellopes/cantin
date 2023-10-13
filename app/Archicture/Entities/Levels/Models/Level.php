<?php

namespace App\Archicture\Entities\Levels\Models;

use App\Archicture\Generics\Models\GenericModels;

class Level extends GenericModels
{
    protected $table = 'levels';

    protected $fillable = [
        'level',
        'description'
    ];
}
