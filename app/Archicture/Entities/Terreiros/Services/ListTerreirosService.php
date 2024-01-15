<?php

namespace App\Archicture\Entities\Terreiros\Services;

use App\Archicture\Entities\Terreiros\Interface\IListTerreirosService;
use App\Archicture\Entities\Terreiros\Models\Terreiro;
use Illuminate\Database\Eloquent\Collection;

class ListTerreirosService implements IListTerreirosService
{
    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return Terreiro::query()
            ->with(['address', 'nation', 'question'])
            ->get();
    }
}
