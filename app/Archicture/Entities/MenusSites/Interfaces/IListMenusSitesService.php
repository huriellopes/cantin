<?php

namespace App\Archicture\Entities\MenusSites\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IListMenusSitesService
{
    /**
     * @return Collection
     */
    public function list(): Collection;
}
