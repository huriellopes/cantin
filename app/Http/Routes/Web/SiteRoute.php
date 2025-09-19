<?php

namespace App\Http\Routes\Web;

use App\Http\Controllers\Web\Auth\LoginController;
use App\Livewire\Site\Pages\PartnersEntities;
use App\Livewire\Site\Pages\StaticPage;
use App\Livewire\Site\Pages\Terreiros\Create;
use App\Livewire\Site\Pages\Transpeople;
use App\Livewire\Site\Pages\About;
use App\Livewire\Site\Pages\Auth\Login;
use App\Livewire\Site\Pages\Blog\Posts;
use App\Livewire\Site\Pages\Blog\Show;
use App\Livewire\Site\Pages\ExternalLinks;
use App\Livewire\Site\Pages\Home;
use App\Livewire\Site\Pages\Terreiros\Search;
use Illuminate\Support\Facades\Route;

class SiteRoute
{
    /**
     * @return void
     */
    public static function web() : void
    {
        Route::name('site.')
            ->middleware('visit_register')
            ->prefix('/')
            ->group(function () {
                Route::get('/', Home::class)
                    ->name('home');
                Route::get('/sobre', About::class)
                    ->name('about');
                Route::get('/entidades-parceiras', PartnersEntities::class)
                    ->name('partners-entities');
                Route::get('/pessoas-trans', Transpeople::class)
                    ->name('trans-people');

                // Terreiro Route
                Route::name('terreiros.')
                    ->prefix('terreiros')
                    ->group(function () {
                        Route::get('/', Search::class)
                            ->name('search');
                        Route::get('/cadastro', Create::class)
                            ->name('create');
                    });

                Route::name('blog.')
                    ->prefix('blog')
                    ->group(function () {
                        Route::get('/', Posts::class)
                            ->name('posts');
                        Route::get('/{post}', Show::class)
                            ->name('show');
                    });

                Route::name('links.')
                    ->prefix('links')
                    ->group(function () {
                        Route::get('/', ExternalLinks::class)->name('external');
                    });

                Route::name('static.')
                    ->prefix('paginas-estaticas')
                    ->group(function () {
                        Route::get('/{staticPage}', StaticPage::class)
                            ->name('page');
                    });

                Route::name('auth.')
                    ->prefix('login')
                    ->group(function () {
                        Route::get('/', Login::class)
                            ->name('login');
                        Route::post('/', [LoginController::class, 'login'])
                            ->name('login.post');
                    });
            });
    }
}
