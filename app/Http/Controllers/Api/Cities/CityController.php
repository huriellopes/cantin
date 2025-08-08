<?php

namespace App\Http\Controllers\Api\Cities;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Http\Resources\CityResource;
use App\Services\CityService;

class CityController extends Controller
{
    public function __invoke(CityRequest $request)
    {
        $city = app(CityService::class)
            ->list($request);

        return CityResource::collection($city);
    }
}
