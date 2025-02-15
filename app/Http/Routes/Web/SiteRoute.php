<?php

namespace App\Http\Routes\Web;

use App\Livewire\Cantin\Pages\Home;
use App\Livewire\Cantin\Pages\About;
use App\Livewire\Cantin\Pages\PartnersEntities;
use App\Livewire\Cantin\Pages\Transpeople;
use App\Livewire\Cantin\Pages\Terreiros\Search;
use App\Livewire\Cantin\Pages\Terreiros\Create;
use App\Livewire\Cantin\Pages\Contact;
use Illuminate\Support\Facades\Route;

class SiteRoute
{
    /**
     * @return void
     */
    public static function web() : void
    {
        Route::prefix('/')
            ->name('site.')
            ->group(function () {
                Route::get('/', Home::class)->name('home');
                Route::get('/sobre', About::class)->name('about');
                Route::get('/entidades-parceiras', PartnersEntities::class)->name('partners-entities');
                Route::get('/pessoas-trans', Transpeople::class)->name('trans-people');

                // Terreiro Route
                Route::prefix('terreiros')
                    ->name('terreiros.')
                    ->group(function () {
                        Route::get('/', Search::class)->name('search');
                        Route::get('/cadastro', Create::class)->name('create');
                    });

                Route::get('contato', Contact::class)->name('contact');
            });
    }
}
