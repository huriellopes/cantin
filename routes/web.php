<?php

declare(strict_types=1);

use App\Http\Routes\Web\AdminRoute;
use App\Http\Routes\Web\SiteRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

SiteRoute::web();
AdminRoute::web();

// Troca de idioma: guarda o locale na sessão e volta para a página anterior.
Route::get('/locale/{locale}', function (string $locale, Request $request): RedirectResponse {
    if (array_key_exists($locale, config('app.available_locales', []))) {
        $request->session()->put('locale', $locale);
    }

    return back();
})->name('locale.switch');
