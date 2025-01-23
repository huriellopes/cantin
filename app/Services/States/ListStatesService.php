<?php

namespace App\Services\States;

use App\Models\State;
use Illuminate\Database\Eloquent\Collection;

class ListStatesService
{
    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return State::query()
            ->select('id', 'acronym', 'description')
            ->get();
    }
}
