<?php

namespace App\Providers;

use App\Archicture\Entities\Addresses\Interface\ICreateAddressService;
use App\Archicture\Entities\Addresses\Services\CreateAddressService;
use App\Archicture\Entities\CEP\Interfaces\IGetCepService;
use App\Archicture\Entities\CEP\Services\GetCepService;
use App\Archicture\Entities\Cities\Interface\IListCitiesService;
use App\Archicture\Entities\Cities\Services\ListCitiesService;
use App\Archicture\Entities\Levels\Enum\LevelEnum;
use App\Archicture\Entities\Levels\Interfaces\IListLevelService;
use App\Archicture\Entities\Levels\Services\ListLevelService;
use App\Archicture\Entities\States\Interface\IListStatesService;
use App\Archicture\Entities\States\Services\ListStatesService;
use App\Archicture\Entities\Terreiros\Interface\IListTerreirosService;
use App\Archicture\Entities\Terreiros\Services\ListTerreirosService;
use App\Archicture\Entities\Users\Interfaces\IDelUsersService;
use App\Archicture\Entities\Users\Interfaces\IListUsersService;
use App\Archicture\Entities\Users\Interfaces\IRestoreUsersService;
use App\Archicture\Entities\Users\Models\User;
use App\Archicture\Entities\Users\Services\DelUsersService;
use App\Archicture\Entities\Users\Services\ListUsersService;
use App\Archicture\Entities\Users\Services\RestoreUsersService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->levels();

        $this->users();

        $this->cep();

        $this->address();

        $this->cities();

        $this->states();

        $this->terreiros();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('view_users', function (User $user) {
            return $user->level_id === LevelEnum::SUPER->value && Auth::user()->level_id === $user->level_id;
        });

        Model::shouldBeStrict(!$this->app->isProduction());
    }

    /**
     * @return void
     */
    private function levels() : void
    {
        $this->app->singleton(
            IListLevelService::class,
            ListLevelService::class
        );
    }

    /**
     * @return void
     */
    private function users() : void
    {
        $this->app->singleton(
            IListUsersService::class,
            ListUsersService::class
        );

        $this->app->singleton(
            IDelUsersService::class,
            DelUsersService::class
        );

        $this->app->singleton(
            IRestoreUsersService::class,
            RestoreUsersService::class
        );
    }

    /**
     * @return void
     */
    private function cep() : void
    {
        $this->app->singleton(
            IGetCepService::class,
            GetCepService::class
        );
    }

    /**
     * @return void
     */
    private function address() : void
    {
        $this->app->singleton(
            ICreateAddressService::class,
            CreateAddressService::class
        );
    }

    private function cities() : void
    {
        $this->app->singleton(
            IListCitiesService::class,
            ListCitiesService::class
        );
    }

    private function states() : void
    {
        $this->app->singleton(
            IListStatesService::class,
            ListStatesService::class
        );
    }

    private function terreiros() : void
    {
        $this->app->singleton(
            IListTerreirosService::class,
            ListTerreirosService::class
        );
    }
}
