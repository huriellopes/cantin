<?php

namespace App\Services\States;

use App\Models\State;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ListStatesService
{
    /**
     * @return Collection
     */
    public static function list(): Collection
    {
        return Cache::remember('states', $seconds = 600, function () {
            return State::query()
                ->select('id', 'acronym', 'description')
                ->get();
        });
    }
}
