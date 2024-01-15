<?php

namespace App\Http\Routes\Api;

use App\Http\Controllers\Api\PartnersEntities\CreatePartnersEntitiesController;
use Illuminate\Support\Facades\Route;

class PartnersEntitiesRoute
{
    /**
     * @return void
     */
    public static function api() : void
    {
        // TODO: Implement api() method.
    }

    /**
     * @return void
     */
    public static function web() : void
    {
        Route::prefix('api')
            ->group(function () {
                Route::prefix('entidades')
                    ->name('entidades.')
                    ->group(function () {
                        Route::post('/store', CreatePartnersEntitiesController::class)->name('store');
                    });
            });
    }
}
