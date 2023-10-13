<?php

namespace App\Archicture\Entities\States\Models;

use App\Archicture\Generics\Models\GenericModels;

class State extends GenericModels
{
    protected $table = "states";

    protected $fillable = [
        'acronym',
        'description'
    ];
}
