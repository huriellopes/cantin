<?php

namespace App\Archicture\Entities\NationsTerreiros\Services;

use App\Archicture\Entities\NationsTerreiros\Interface\IListNationsTerreirosService;
use App\Archicture\Entities\NationsTerreiros\Models\NationsTerreiro;
use Illuminate\Database\Eloquent\Collection;

class ListNationsTerreirosService implements IListNationsTerreirosService
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
