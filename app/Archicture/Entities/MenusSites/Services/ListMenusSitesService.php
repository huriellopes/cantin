<?php

namespace App\Archicture\Entities\MenusSites\Services;

use App\Archicture\Entities\MenusSites\Interfaces\IListMenusSitesService;
use App\Archicture\Entities\MenusSites\Models\MenuSite;
use App\Archicture\Entities\StatusMenusSites\Enum\StatusMenuSiteEnum;
use Illuminate\Database\Eloquent\Collection;

class ListMenusSitesService implements IListMenusSitesService
{

    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return MenuSite::query()
            ->active()
            ->get();
    }
}
