<?php

namespace App\Services\MenusSites;

use App\Models\MenuSite;
use Illuminate\Database\Eloquent\Collection;

class ListMenusSitesService
{

    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return MenuSite::query()
            ->select('id','name','description','route','status','user_id')
            ->active()
            ->get();
    }
}
