<?php

namespace App\Archicture\Entities\Levels\Services;

use App\Archicture\Entities\Levels\Interfaces\IListLevelService;
use App\Archicture\Entities\Levels\Models\Level;
use Illuminate\Database\Eloquent\Collection;

class ListLevelService implements IListLevelService
{
    /**
     * @return Collection
     */
    public function listLevels(): Collection
    {
        return Level::all();
    }
}
