<?php

namespace App\Http\Controllers\Web\Site\Pages;

use App\Archicture\Entities\MenusSites\Actions\ListMenusSitesAction;
use App\Archicture\Entities\States\Actions\ListStatesAction;
use App\Http\Controllers\Web\WebBaseController;

class SearchTerreirosController extends WebBaseController
{
    public function __construct(
        ListMenusSitesAction $listMenusSitesAction,
        protected ListStatesAction $listStatesAction
    )
    {
        parent::__construct($listMenusSitesAction);
    }

    public function __invoke()
    {
        $menus = $this->listMenusSitesAction->execute();
        $states = $this->listStatesAction->execute();

        return view($this->viewPath.'Terreiros.search', compact('menus', 'states'));
    }
}
