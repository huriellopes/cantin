<?php

namespace App\Http\Controllers\Web\Site\Pages;

use App\Http\Controllers\Web\WebBaseController;

class CreateTerreirosController extends WebBaseController
{
    public function __invoke()
    {
        $menus = $this->listMenusSitesService->list();

        return view($this->viewPath.'Terreiros.create', compact('menus'));
    }
}
