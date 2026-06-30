<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\State;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class StateService
{
    public function list(): Collection
    {
        return Cache::remember('states', 60 * 60 * 24, fn () => State::query()
            ->get());
    }
}
