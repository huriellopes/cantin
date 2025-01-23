<?php

namespace App\Http\Controllers\Web\Site\Pages;

use App\Models\Terreiro;
use App\Http\Controllers\Web\WebBaseController;

class CreateTerreiroQuestionController extends WebBaseController
{
    public function __invoke(int $id)
    {
        if (!$this->verifyTerreiro($id)) {
            return redirect()->route('home');
        }

        $menus = $this->listMenusSitesService->list();

        return view($this->viewPath.'Terreiros.create-question', compact('menus', 'id'));
    }

    /**
     * @param int $id
     * @return bool
     */
    private function verifyTerreiro(int $id) : bool
    {
        return Terreiro::query()->where('id', '=', $id)->exists();
    }
}
