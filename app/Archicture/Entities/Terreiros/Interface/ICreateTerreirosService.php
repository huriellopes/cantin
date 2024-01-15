<?php

namespace App\Archicture\Entities\Terreiros\Interface;

use App\Archicture\Entities\Terreiros\Models\Terreiro;

interface ICreateTerreirosService
{
    /**
     * @param object $params
     * @return Terreiro
     */
    public function create(object $params) : Terreiro;
}
