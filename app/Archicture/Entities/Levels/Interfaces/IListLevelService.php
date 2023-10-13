<?php

namespace App\Archicture\Entities\Levels\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IListLevelService
{

    /**
     * @return Collection
     */
    public function listLevels() : Collection;
}
