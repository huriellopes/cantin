<?php

namespace App\Http\Routes\Api;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Levels\ListLevelController;

class LevelRoute
{
    /**
     * @return void
     */
    public static function api() : void
    {
        Route::middleware(['auth', 'verified'])
            ->prefix('levels')
            ->group(function () {
                Route::get('/list', ListLevelController::class);
            });
    }
}
