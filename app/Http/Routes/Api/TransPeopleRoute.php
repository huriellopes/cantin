<?php

namespace App\Http\Routes\Api;

use App\Http\Controllers\Api\TransPeople\CreateTransPeopleController;
use Illuminate\Support\Facades\Route;

class TransPeopleRoute
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
                Route::prefix('transpeople')
                    ->name('transpeople.')
                    ->group(function () {
                        Route::post('/store', CreateTransPeopleController::class)->name('store');
                    });
            });
    }
}
