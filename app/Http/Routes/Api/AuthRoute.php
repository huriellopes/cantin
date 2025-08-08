<?php

namespace App\Http\Routes\Api;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;

final class AuthRoute
{
    /**
     * @return void
     */
    public static function api() : void
    {
        Route::name('auth.')
            ->prefix('auth')
            ->middleware(['throttle:10,1'])
            ->group(function () {
                Route::get('/login', [LoginController::class, 'login'])
                    ->name('login');

                Route::post('/register', [LoginController::class, 'register'])
                    ->name('register');
            });
    }
}
