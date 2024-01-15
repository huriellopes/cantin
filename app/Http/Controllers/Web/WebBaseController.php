<?php

namespace App\Http\Controllers\Web;

use App\Archicture\Entities\MenusSites\Actions\ListMenusSitesAction;
use App\Http\Controllers\Controller;

class WebBaseController extends Controller
{
    protected string $viewPath = 'Site.Pages.';

    /**
     * @param ListMenusSitesAction $listMenusSitesAction
     */
    public function __construct(
        protected ListMenusSitesAction $listMenusSitesAction,
    ){}
}
