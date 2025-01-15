<?php

namespace App\Http\Controllers\Web;

use App\Archicture\Entities\MenusSites\Actions\ListMenusSitesAction;
use App\Http\Controllers\Controller;
use App\Traits\Utils;

class WebBaseController extends Controller
{
    use Utils;
    protected string $viewPath = 'Site.Pages.';

    /**
     * @param ListMenusSitesAction $listMenusSitesAction
     */
    public function __construct(
        protected ListMenusSitesAction $listMenusSitesAction,
    ){}
}
