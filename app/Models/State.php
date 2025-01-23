<?php

namespace App\Models;

class State extends GenericModels
{
    protected $table = "states";

    protected $fillable = [
        'acronym',
        'description'
    ];
}
