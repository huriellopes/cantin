<?php

namespace App\Services\Levels;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class ListLevelService
{
    /**
     * @return Collection
     */
    public function listLevels(): Collection
    {
        return Role::query()
            ->select('id', 'level', 'description')
            ->get();
    }
}
