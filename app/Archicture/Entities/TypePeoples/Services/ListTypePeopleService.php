<?php

namespace App\Archicture\Entities\TypePeoples\Services;

use App\Archicture\Entities\TypePeoples\Interface\IListTypePeopleService;
use App\Archicture\Entities\TypePeoples\Models\TypePeople;
use Illuminate\Database\Eloquent\Collection;

class ListTypePeopleService implements IListTypePeopleService
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
