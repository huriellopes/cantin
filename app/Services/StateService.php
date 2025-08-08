<?php

namespace App\Services;

use App\Models\State;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class StateService
{
    /**
     * @return Collection
     */
    public function list() : Collection
    {
        return Cache::remember('states', 60 * 60 * 24, function () {
            return State::query()
                ->get();
        });
    }
}
