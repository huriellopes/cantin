<?php

namespace App\Archicture\Entities\Terreiros\Interface;

use Illuminate\Database\Eloquent\Collection;

interface IListTerreirosService
{
    /**
     * @return Collection
     */
    public function list() : Collection;
}
