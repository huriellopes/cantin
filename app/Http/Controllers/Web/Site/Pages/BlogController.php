<?php

namespace App\Http\Controllers\Web\Site\Pages;

use App\Http\Controllers\Web\WebBaseController;

class BlogController extends WebBaseController
{
    public function __invoke()
    {
        $menus = $this->listMenusSitesService->list();

        return view($this->viewPath.'Blog.index', compact('menus'));
    }
}
