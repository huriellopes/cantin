<?php

namespace App\Archicture\Entities\States\Services;

use App\Archicture\Entities\States\Interface\IListStatesService;
use App\Archicture\Entities\States\Models\State;
use Illuminate\Database\Eloquent\Collection;

class ListStatesService implements IListStatesService
{
    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return State::all();
    }
}
