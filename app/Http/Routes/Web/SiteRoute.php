<?php

namespace App\Http\Routes\Web;

use App\Http\Controllers\Web\Auth\LoginController;
use App\Livewire\Cantin\Pages\Auth\AuthFlip;
use App\Livewire\Cantin\Pages\Auth\Login;
use App\Livewire\Cantin\Pages\Auth\Register;
use App\Livewire\Cantin\Pages\Home;
use App\Livewire\Cantin\Pages\About;
use App\Livewire\Cantin\Pages\PartnersEntities;
use App\Livewire\Cantin\Pages\Transpeople;
use App\Livewire\Cantin\Pages\Terreiros\Search;
use App\Livewire\Cantin\Pages\Terreiros\Create;
use App\Livewire\Cantin\Pages\Blog\Posts;
use App\Livewire\Cantin\Pages\Blog\Show;
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

                Route::name('auth.')
                    ->prefix('login')
                    ->group(function () {
                        Route::get('/', AuthFlip::class)
                            ->name('login-cantin');
                        Route::post('/', [LoginController::class, 'login'])->name('login-post');
                        Route::post('/register', [LoginController::class, 'register'])
                            ->name('register.post');
                    });
            });
    }
}
