<?php

namespace App\Http\Routes\Web;

use App\Livewire\Admin\Comments\Index as CommentsIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Posts\Index as PostsIndex;
use App\Livewire\Admin\Terreiros\Index as TerreirosIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;
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

                Route::get('/terreiros', TerreirosIndex::class)->name('terreiros.index');
                Route::get('/comments', CommentsIndex::class)->name('comments.index');
                Route::get('/posts', PostsIndex::class)->name('posts.index');

                // Apenas super-admin
                Route::get('/users', UsersIndex::class)
                    ->middleware('role:super-admin')
                    ->name('users.index');

                Route::post('/logout', function () {
                    Auth::logout();
                    request()->session()->invalidate();
                    request()->session()->regenerateToken();

                    return redirect()->route('site.home');
                })->name('logout');
            });
    }
}
