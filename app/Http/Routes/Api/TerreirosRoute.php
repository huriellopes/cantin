<?php

namespace App\Http\Routes\Api;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Terreiros\ListTerreirosController;

class TerreirosRoute
{
    public static function api() : void
    {
        Route::middleware(['auth', 'verified'])
            ->prefix('terreiros')
            ->group(function () {
                Route::get('/list', ListTerreirosController::class);
            });
    }
}
