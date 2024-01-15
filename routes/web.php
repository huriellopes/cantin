<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

\App\Http\Routes\Web\SiteRoute::web();

\App\Http\Routes\Api\TerreirosRoute::web();

\App\Http\Routes\Api\CitiesStatesRoute::web();

\App\Http\Routes\Api\TypePeopleRoute::web();

\App\Http\Routes\Api\SuggestionRoute::web();

\App\Http\Routes\Api\PartnersEntitiesRoute::web();

\App\Http\Routes\Api\TransPeopleRoute::web();

Route::group([
    'middleware' => ['auth', 'verified']
], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/users', \App\Http\Controllers\Web\Users\ViewUsersController::class)
        ->middleware('can:view_users')
        ->name('users');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([
    'prefix' => 'api'
], function () {
    \App\Http\Routes\Api\LevelRoute::api();

    \App\Http\Routes\Api\UsersRoute::api();

    \App\Http\Routes\Api\TerreirosRoute::api();

    \App\Http\Routes\Api\PartnersRoute::api();
});

require __DIR__.'/auth.php';
