<?php

namespace App\Http\Routes\Api;

use App\Http\Controllers\Api\NationsTerreiros\ListNationsTerreirosController;
use App\Http\Controllers\Api\Terreiros\CreateTerreiroQuestionController;
use App\Http\Controllers\Api\Terreiros\CreateTerreirosController;
use App\Http\Controllers\Api\Terreiros\SearchTerreiroForUfController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Terreiros\ListTerreirosController;

class TerreirosRoute
{
    public static function api() : void
    {
        Route::prefix('terreiros')
//            ->middleware(['auth', 'verified'])
            ->group(function () {
                Route::get('/list', ListTerreirosController::class);
            });
    }

    public static function web() : void
    {
        Route::prefix('api')
            ->group(function () {
                Route::prefix('terreiros')
                    ->name('terreiros.')
                    ->group(function () {
                        Route::post('/store', CreateTerreirosController::class)->name('store');
                        Route::post('/question/{id}/store', CreateTerreiroQuestionController::class)->name('question.store');
                        Route::post('/search?uf={state_id}', SearchTerreiroForUfController::class)->name('search');
                    });

                Route::prefix('nacoes')
                    ->group(function () {
                        Route::get('/list', ListNationsTerreirosController::class);
                    });
            });
    }
}
