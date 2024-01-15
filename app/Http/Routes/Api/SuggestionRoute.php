<?php

namespace App\Http\Routes\Api;

use App\Http\Controllers\Api\Suggestions\ListSuggestionController;
use Illuminate\Support\Facades\Route;

class SuggestionRoute
{
    /**
     * @return void
     */
    public static function web(): void
    {
        Route::prefix('api')
            ->group(function () {
                Route::prefix('suggestions')
                    ->group(function () {
                        Route::get('/list', ListSuggestionController::class);
                    });
            });
    }
}
