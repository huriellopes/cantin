<?php

namespace App\Http\Routes\Web;

use App\Livewire\Admin\Categories\Index as CategoriesIndex;
use App\Livewire\Admin\Comments\Index as CommentsIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Nations\Index as NationsIndex;
use App\Livewire\Admin\Pages\Index as PagesIndex;
use App\Livewire\Admin\Posts\Index as PostsIndex;
use App\Livewire\Admin\StaticPages\Index as StaticPagesIndex;
use App\Livewire\Admin\Terreiros\Index as TerreirosIndex;
use App\Livewire\Admin\TypeExternalLinks\Index as TypeExternalLinksIndex;
use App\Livewire\Admin\TypePeoples\Index as TypePeoplesIndex;
use App\Livewire\Admin\TypeTerreiros\Index as TypeTerreirosIndex;
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
                Route::get('/categories', CategoriesIndex::class)->name('categories.index');
                Route::get('/nations', NationsIndex::class)->name('nations.index');
                Route::get('/type-terreiros', TypeTerreirosIndex::class)->name('type-terreiros.index');
                Route::get('/type-peoples', TypePeoplesIndex::class)->name('type-peoples.index');
                Route::get('/type-external-links', TypeExternalLinksIndex::class)->name('type-external-links.index');
                Route::get('/pages', PagesIndex::class)->name('pages.index');
                Route::get('/static-pages', StaticPagesIndex::class)->name('static-pages.index');

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
