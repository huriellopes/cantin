<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\MenusSites\ListMenusSitesService;
use App\Traits\Utils;

class WebBaseController extends Controller
{
    use Utils;
    protected string $viewPath = 'Site.Pages.';

    /**
     * @param ListMenusSitesService $listMenusSitesService
     */
    public function __construct(
        protected ListMenusSitesService $listMenusSitesService,
    ){}
}
