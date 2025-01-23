<?php

namespace App\Models;

class Level extends GenericModels
{
    protected $table = 'levels';

    protected $fillable = [
        'level',
        'description'
    ];
}
