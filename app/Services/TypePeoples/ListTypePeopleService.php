<?php

namespace App\Services\TypePeoples;

use App\Models\TypePeople;
use Illuminate\Database\Eloquent\Collection;

class ListTypePeopleService
{
    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return TypePeople::query()
            ->select('id', 'type', 'description')
            ->get();
    }
}
