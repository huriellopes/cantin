<?php

namespace App\Providers;

use App\Archicture\Entities\Addresses\Interface\ICreateAddressService;
use App\Archicture\Entities\Addresses\Services\CreateAddressService;
use App\Archicture\Entities\CEP\Interfaces\IGetCepService;
use App\Archicture\Entities\CEP\Services\GetCepService;
use App\Archicture\Entities\Cities\Interface\IListCitiesService;
use App\Archicture\Entities\Cities\Services\ListCitiesService;
use App\Archicture\Entities\CommonQuestion\Interfaces\IListCommonQuestionService;
use App\Archicture\Entities\CommonQuestion\Services\ListCommonQuestionService;
use App\Archicture\Entities\Levels\Enum\LevelEnum;
use App\Archicture\Entities\Levels\Interfaces\IListLevelService;
use App\Archicture\Entities\Levels\Services\ListLevelService;
use App\Archicture\Entities\Logs\Interfaces\ICreateLogService;
use App\Archicture\Entities\Logs\Interfaces\IListLogService;
use App\Archicture\Entities\Logs\Services\CreateLogService;
use App\Archicture\Entities\Logs\Services\ListLogService;
use App\Archicture\Entities\MenusSites\Interfaces\IListMenusSitesService;
use App\Archicture\Entities\MenusSites\Services\ListMenusSitesService;
use App\Archicture\Entities\NationsTerreiros\Interface\IListNationsTerreirosService;
use App\Archicture\Entities\NationsTerreiros\Services\ListNationsTerreirosService;
use App\Archicture\Entities\Partners\Interface\ICreatePartnersService;
use App\Archicture\Entities\Partners\Interface\IListPartnersService;
use App\Archicture\Entities\Partners\Interface\IUpdatePartnersService;
use App\Archicture\Entities\Partners\Services\CreatePartnersService;
use App\Archicture\Entities\Partners\Services\ListPartnersService;
use App\Archicture\Entities\Partners\Services\UpdatePartnersService;
use App\Archicture\Entities\PartnersEntities\Interfaces\ICreatePartnersEntitiesService;
use App\Archicture\Entities\PartnersEntities\Services\CreatePartnersEntitiesService;
use App\Archicture\Entities\States\Interface\IListStatesService;
use App\Archicture\Entities\States\Services\ListStatesService;
use App\Archicture\Entities\Status\Interfaces\IListStatusService;
use App\Archicture\Entities\Status\Services\ListStatusService;
use App\Archicture\Entities\Suggestions\Interface\IListSuggestionService;
use App\Archicture\Entities\Suggestions\Services\ListSuggestionService;
use App\Archicture\Entities\Terreiros\Interface\ICreateTerreirosService;
use App\Archicture\Entities\Terreiros\Interface\IListTerreirosService;
use App\Archicture\Entities\Terreiros\Interface\ISearchTerreiroForUFService;
use App\Archicture\Entities\Terreiros\Services\CreateTerreirosService;
use App\Archicture\Entities\Terreiros\Services\ListTerreirosService;
use App\Archicture\Entities\Terreiros\Services\SearchTerreiroForUFService;
use App\Archicture\Entities\TerreirosQuestions\Interfaces\ICreateTerreiroQuestionService;
use App\Archicture\Entities\TerreirosQuestions\Services\CreateTerreiroQuestionService;
use App\Archicture\Entities\TransPeople\Interfaces\ICreateTransPeopleService;
use App\Archicture\Entities\TransPeople\Services\CreateTransPeopleService;
use App\Archicture\Entities\TypePeoples\Interface\IListTypePeopleService;
use App\Archicture\Entities\TypePeoples\Services\ListTypePeopleService;
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
        $this->site();

        $this->levels();

        $this->users();

        $this->cep();

        $this->address();

        $this->cities();

        $this->states();

        $this->terreiros();

        $this->nations();

        $this->typePeoples();

        $this->suggestions();

        $this->status();

        $this->partners();

        $this->partnersEntities();

        $this->commonQuestions();

        $this->transPeoples();

        $this->logs();
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
    public function site() : void
    {
        $this->app->singleton(
            IListMenusSitesService::class,
            ListMenusSitesService::class
        );
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

    /**
     * @return void
     */
    private function terreiros() : void
    {
        $this->app->singleton(
            IListTerreirosService::class,
            ListTerreirosService::class
        );

        $this->app->singleton(
            ICreateTerreirosService::class,
            CreateTerreirosService::class
        );

        $this->app->singleton(
            ICreateTerreiroQuestionService::class,
            CreateTerreiroQuestionService::class
        );

        $this->app->singleton(
            ISearchTerreiroForUFService::class,
            SearchTerreiroForUFService::class
        );
    }

    /**
     * @return void
     */
    private function nations() : void
    {
        $this->app->singleton(
            IListNationsTerreirosService::class,
            ListNationsTerreirosService::class
        );
    }

    /**
     * @return void
     */
    private function typePeoples() : void
    {
        $this->app->singleton(
            IListTypePeopleService::class,
            ListTypePeopleService::class
        );
    }

    /**
     * @return void
     */
    private function suggestions() : void
    {
        $this->app->singleton(
            IListSuggestionService::class,
            ListSuggestionService::class
        );
    }

    /**
     * @return void
     */
    private function status() : void
    {
        $this->app->singleton(
            IListStatusService::class,
            ListStatusService::class
        );
    }

    /**
     * @return void
     */
    private function partners() : void
    {
        $this->app->singleton(
            IListPartnersService::class,
            ListPartnersService::class
        );

        $this->app->singleton(
            IUpdatePartnersService::class,
            UpdatePartnersService::class
        );

        $this->app->singleton(
            ICreatePartnersService::class,
            CreatePartnersService::class
        );
    }

    /**
     * @return void
     */
    private function partnersEntities() : void
    {
        $this->app->singleton(
            ICreatePartnersEntitiesService::class,
            CreatePartnersEntitiesService::class
        );
    }

    /**
     * @return void
     */
    private function commonQuestions() : void
    {
        $this->app->singleton(
            IListCommonQuestionService::class,
            ListCommonQuestionService::class
        );
    }

    /**
     * @return void
     */
    private function transPeoples() : void
    {
        $this->app->singleton(
            ICreateTransPeopleService::class,
            CreateTransPeopleService::class
        );
    }

    /**
     * @return void
     */
    private function logs() : void
    {
        $this->app->singleton(
            IListLogService::class,
            ListLogService::class
        );

        $this->app->singleton(
            ICreateLogService::class,
            CreateLogService::class
        );
    }
}
