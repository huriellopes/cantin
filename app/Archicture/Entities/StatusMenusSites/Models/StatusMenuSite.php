<?php

namespace App\Archicture\Entities\StatusMenusSites\Models;

use App\Archicture\Generics\Models\GenericModels;

class StatusMenuSite extends GenericModels
{
    protected $table = 'status_menus_sites';

    protected $fillable = [
        'name',
        'description',
    ];
}
