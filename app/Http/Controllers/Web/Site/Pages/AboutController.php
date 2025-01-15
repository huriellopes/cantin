<?php

namespace App\Http\Controllers\Web\Site\Pages;

use App\Archicture\Entities\MenusSites\Actions\ListMenusSitesAction;
use App\Http\Controllers\Web\WebBaseController;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

class AboutController extends WebBaseController
{
    public function __invoke()
    {
        $menus = $this->listMenusSitesAction->execute();

        $this->webhook('error', null, 'Teste de erro', null);

        return view($this->viewPath.'About.index', compact('menus'));
    }
}
