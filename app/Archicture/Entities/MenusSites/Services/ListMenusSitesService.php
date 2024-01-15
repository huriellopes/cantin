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
        return MenuSite::with('statusMenuSite')
            ->whereHas('statusMenuSite', function ($query) {
                return $query->where('status_menus_sites_id', '=', StatusMenuSiteEnum::ACTIVE->value);
            })->get();
    }
}
