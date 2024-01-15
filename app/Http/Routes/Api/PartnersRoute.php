<?php

namespace App\Http\Routes\Api;

use App\Http\Controllers\Api\Partners\CreatePartnersController;
use App\Http\Controllers\Api\Partners\ListPartnersController;
use App\Http\Controllers\Api\Partners\UpdatePartnersController;
use Illuminate\Support\Facades\Route;

class PartnersRoute
{
    /**
     * @return void
     */
    public static function api() : void
    {
        Route::prefix('partners')
            ->group(function () {
                Route::get('/list', ListPartnersController::class);
                Route::post('/store', CreatePartnersController::class);
                Route::put('/{partner}/update', UpdatePartnersController::class);
            });
    }
}
