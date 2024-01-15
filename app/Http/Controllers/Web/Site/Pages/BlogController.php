<?php

namespace App\Http\Controllers\Web\Site\Pages;

use App\Archicture\Entities\MenusSites\Actions\ListMenusSitesAction;
use App\Http\Controllers\Web\WebBaseController;

class BlogController extends WebBaseController
{
    public function __invoke()
    {
        $menus = $this->listMenusSitesAction->execute();

        return view($this->viewPath.'Blog.index', compact('menus'));
    }
}
