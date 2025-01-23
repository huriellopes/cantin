<?php

namespace App\Services\Levels;

use App\Models\Level;
use Illuminate\Database\Eloquent\Collection;

class ListLevelService
{
    /**
     * @return Collection
     */
    public function listLevels(): Collection
    {
        return Level::query()
            ->select('id', 'level', 'description')
            ->get();
    }
}
