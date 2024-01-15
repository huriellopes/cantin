<?php

namespace App\Archicture\Entities\NationsTerreiros\Interface;

use Illuminate\Database\Eloquent\Collection;

interface IListNationsTerreirosService
{
    /**
     * @return Collection
     */
    public function list() : Collection;
}
