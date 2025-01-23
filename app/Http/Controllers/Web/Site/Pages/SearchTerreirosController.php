<?php

namespace App\Http\Controllers\Web\Site\Pages;

use App\Http\Controllers\Web\WebBaseController;
use App\Http\Requests\Terreiros\SearchTerreiroForUFRequest;
use App\Services\MenusSites\ListMenusSitesService;
use App\Services\States\ListStatesService;
use App\Services\Terreiros\SearchTerreiroForUFService;

class SearchTerreirosController extends WebBaseController
{
    /**
     * @param SearchTerreiroForUFService $searchTerreiroForUFService
     * @param ListMenusSitesService $listMenusSitesService
     * @param ListStatesService $listStatesService
     */
    public function __construct(
        protected SearchTerreiroForUFService $searchTerreiroForUFService,
        protected ListMenusSitesService $listMenusSitesService,
        protected ListStatesService $listStatesService
    )
    {
        parent::__construct($listMenusSitesService);
    }

    public function __invoke(SearchTerreiroForUFRequest $request)
    {
        $menus = $this->listMenusSitesService->list();
        $states = $this->listStatesService->list();
        $terreiros = $this->searchTerreiroForUFService->search($request);

        return view($this->viewPath.'Terreiros.search', compact('menus', 'states', 'terreiros'));
    }


}
