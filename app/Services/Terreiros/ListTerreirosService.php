<?php

namespace App\Services\Terreiros;

use App\Models\Terreiro;
use Illuminate\Database\Eloquent\Collection;

class ListTerreirosService
{
    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return Terreiro::query()
            ->select('id',
                'name',
                'phone',
                'fundationed_at',
                'nation_terreiro_id',
                'leadership_orunko',
                'color_of_leadership',
                'address_id',
                'created_at')
            ->with(['address', 'nation', 'question'])
            ->get();
    }
}
