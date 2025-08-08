<?php

namespace App\Services;

use App\Http\Requests\CityRequest;
use App\Models\City;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CityService
{
    /**
     * @param CityRequest $request
     * @return Collection
     */
    public function list(CityRequest $request) : Collection
    {
        return Cache::remember('cities_' . $request->get('state'), 60 * 60 * 24, function () use ($request) {
            return City::query()
                ->when(!empty($request->get('state')), function ($query) use ($request) {
                    $query->where('state_id', '=', $request->get('state'));
                })->get();
        });
    }
}
