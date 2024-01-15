<?php

namespace App\Http\Controllers\Web\Site\Pages;

use App\Archicture\Entities\CommonQuestion\Actions\ListCommonQuestionAction;
use App\Archicture\Entities\MenusSites\Actions\ListMenusSitesAction;
use App\Http\Controllers\Web\WebBaseController;
use Faker\Core\Number;

class HomeController extends WebBaseController
{
    public function __construct(
        protected ListMenusSitesAction $listMenusSitesAction,
        protected ListCommonQuestionAction $listCommonQuestionAction,
    )
    {
        parent::__construct($listMenusSitesAction);
    }

    public function __invoke()
    {
        $menus = $this->listMenusSitesAction->execute();
        $commons = $this->listCommonQuestionAction->execute();

        return view($this->viewPath.'Home.index', compact('menus', 'commons'));
    }
}
