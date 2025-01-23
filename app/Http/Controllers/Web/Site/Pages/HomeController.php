<?php

namespace App\Http\Controllers\Web\Site\Pages;

use App\Http\Controllers\Web\WebBaseController;
use App\Services\CommonQuestion\ListCommonQuestionService;
use App\Services\MenusSites\ListMenusSitesService;

class HomeController extends WebBaseController
{
    public function __construct(
        protected ListMenusSitesService $listMenusSitesService,
        protected ListCommonQuestionService $listCommonQuestionService,
    )
    {
        parent::__construct($listMenusSitesService);
    }

    public function __invoke()
    {
        $menus = $this->listMenusSitesService->list();
        $commons = $this->listCommonQuestionService->list();

        return view($this->viewPath.'Home.index', compact('menus', 'commons'));
    }
}
