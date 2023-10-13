<?php

namespace App\Http\Routes\Api;

use App\Http\Controllers\Api\Users\ListUsersController;
use App\Http\Controllers\Api\Users\RestoreUsersController;
use App\Http\Controllers\Api\Users\DelUsersController;
use Illuminate\Support\Facades\Route;

class UsersRoute
{
    public static function api() : void
    {
        Route::middleware(['auth', 'verified'])
            ->prefix('users')
            ->group(function () {
                Route::get('/list', ListUsersController::class);
                Route::post('/{user}/delete', DelUsersController::class);
                Route::post('/{id}/restore', RestoreUsersController::class);
            });
    }
}
