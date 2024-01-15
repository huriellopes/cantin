<?php

namespace App\Http\Routes\Api;

use App\Http\Controllers\Api\TypePeople\ListTypePeopleController;
use Illuminate\Support\Facades\Route;

class TypePeopleRoute
{
    /**
     * @return void
     */
    public static function web() : void
    {
        Route::prefix('api')
            ->group(function () {
                Route::prefix('type-peoples')
                    ->group(function () {
                        Route::get('/list', ListTypePeopleController::class);
                    });
            });
    }
}
