<?php

namespace App\Http\Controllers\Api\States;

use App\Http\Controllers\Controller;
use App\Http\Resources\StateResource;
use App\Services\StateService;

class StateController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke()
    {
        $state = app(StateService::class)
            ->list();

        return StateResource::collection($state);
    }
}
