<?php

namespace App\Services\NationsTerreiros;

use App\Models\NationsTerreiro;
use Illuminate\Database\Eloquent\Collection;

class ListNationsTerreirosService
{

    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return NationsTerreiro::query()
            ->select('id', 'nation')
            ->get();
    }
}
