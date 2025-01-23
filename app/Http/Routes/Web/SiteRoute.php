<?php

namespace App\Http\Routes\Web;

use App\Archicture\Entities\MenusSites\Models\MenuSite;
use App\Http\Controllers\Web\Site\Pages\AboutController;
use App\Http\Controllers\Web\Site\Pages\BlogController;
use App\Http\Controllers\Web\Site\Pages\ContactController;
use App\Http\Controllers\Web\Site\Pages\CreatePartnersEntitiesController;
use App\Http\Controllers\Web\Site\Pages\CreateTerreiroQuestionController;
use App\Http\Controllers\Web\Site\Pages\CreateTerreirosController;
use App\Http\Controllers\Web\Site\Pages\CreateTransPeopleController;
use App\Http\Controllers\Web\Site\Pages\HomeController;
use App\Http\Controllers\Web\Site\Pages\SearchTerreirosController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;

class SiteRoute
{
    public static function web() : void
    {
        Route::prefix('/')
            ->group(function () {
                Route::get('/', HomeController::class)
                    ->name('home');

                Route::get('/sobre', AboutController::class)
                    ->name('about');

                Route::get('/entidades', CreatePartnersEntitiesController::class)
                    ->name('partners.entities');

                Route::get('/pessoas-trans', CreateTransPeopleController::class)
                    ->name('people.trans');

                Route::get('/blog', BlogController::class)
                    ->name('blog');

                Route::get('/contato', ContactController::class)
                    ->name('contact');

                Route::prefix('terreiros')
                    ->group(function () {
                        Route::get('/cadastro', CreateTerreirosController::class)
                            ->name('create.terreiros');
                        Route::get('/{id}/questoes', CreateTerreiroQuestionController::class)
                            ->name('create.questoes');
                        Route::get('/{uf?}',SearchTerreirosController::class)
                            ->name('search.terreiros');
                    });
            });
    }

    /**
     * @return Collection
     */
    private function menus() : Collection
    {
        return MenuSite::query()
            ->active()
            ->select('id', 'name', 'route')
            ->get();
    }
}
