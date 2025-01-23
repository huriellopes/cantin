<?php

namespace App\Http\Controllers\Web\Site\Pages;

use App\Http\Controllers\Web\WebBaseController;

class CreatePartnersEntitiesController extends WebBaseController
{
    public function __invoke()
    {
        $menus = $this->listMenusSitesService->list();

        return View($this->viewPath."PartnersEntities.create", compact('menus'));
    }
}
