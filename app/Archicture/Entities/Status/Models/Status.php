<?php

namespace App\Archicture\Entities\Status\Models;

use App\Archicture\Generics\Models\GenericModels;

class Status extends GenericModels
{
    protected $table = "status";

    protected $fillable = [
        'name',
        'description',
    ];
}
