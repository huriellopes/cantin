<?php

namespace App\Http\Routes\Api;

use App\Http\Controllers\Api\CEP\GetCepController;
use App\Http\Controllers\Api\Cities\ListCitiesController;
use App\Http\Controllers\Api\States\ListStatesController;
use Illuminate\Support\Facades\Route;

class CitiesStatesRoute
{
    public static function web() : void
    {
        Route::prefix('api')
            ->group(function () {
                Route::prefix('cities')
                    ->group(function () {
                        Route::post('/list', ListCitiesController::class);
                    });

                Route::prefix('states')
                    ->group(function () {
                        Route::get('/list', ListStatesController::class);
                    });

                Route::prefix('cep')
                    ->group(function () {
                        Route::post('/get', GetCepController::class);
                    });
            });
    }
}
