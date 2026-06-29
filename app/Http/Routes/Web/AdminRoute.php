<?php

namespace App\Http\Routes\Web;

use App\Livewire\Admin\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminRoute
{
    public static function web(): void
    {
        Route::name('admin.')
            ->prefix('admin')
            ->middleware(['auth', 'role:admin,super-admin'])
            ->group(function () {
                Route::get('/', Dashboard::class)->name('dashboard');

                Route::post('/logout', function () {
                    Auth::logout();
                    request()->session()->invalidate();
                    request()->session()->regenerateToken();

                    return redirect()->route('site.home');
                })->name('logout');
            });
    }
}
