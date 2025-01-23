<?php

namespace App\Models;

class Status extends GenericModels
{
    protected $table = "status";

    protected $fillable = [
        'name',
        'description',
    ];
}
