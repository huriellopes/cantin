<?php

namespace App\Http\Routes\Api;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommonQuestions\CommonQuestionController;
use App\Http\Controllers\Api\States\StateController;
use App\Http\Controllers\Api\Cities\CityController;

final class SiteApiRoute
{
    /**
     * @return void
     */
    public static function api() : void
    {
        Route::name('site.')
            ->group(function () {
                Route::get('/common', CommonQuestionController::class)
                    ->name('common');

                Route::get('/states', StateController::class)
                    ->name('states');

                Route::get('/cities{state?}', CityController::class)
                    ->name('cities');
            });
    }
}
