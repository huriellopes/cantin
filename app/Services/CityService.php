<?php

namespace App\Services;

use App\Http\Requests\CityRequest;
use App\Models\City;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CityService
{
    public function list(CityRequest $request): Collection
    {
        return Cache::remember('cities_'.$request->get('state'), 60 * 60 * 24, fn () => City::query()
            ->unless(empty($request->get('state')), function ($query) use ($request): void {
                $query->where('state_id', '=', $request->get('state'));
            })->get());
    }
}
