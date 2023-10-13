<?php

namespace App\Http\Routes\Api;

use App\Http\Controllers\Api\Cities\ListCitiesController;
use App\Http\Controllers\Api\States\ListStatesController;
use Illuminate\Support\Facades\Route;

class CitiesStatesRoute
{
    public static function api() : void
    {
        Route::middleware(['auth', 'verified'])
            ->prefix('cities')
            ->group(function () {
                Route::post('/list', ListCitiesController::class);
            });

        Route::middleware(['auth', 'verified'])
            ->prefix('states')
            ->group(function () {
                Route::get('/list', ListStatesController::class);
            });
    }
}
