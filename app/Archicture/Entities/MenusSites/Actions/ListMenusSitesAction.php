<?php

namespace App\Archicture\Entities\MenusSites\Actions;

use App\Archicture\Entities\MenusSites\Interfaces\IListMenusSitesService;
use Illuminate\Database\Eloquent\Collection;

class ListMenusSitesAction
{
    /**
     * @param IListMenusSitesService $IlistMenusSitesService
     */
    public function __construct(
        protected IListMenusSitesService $IlistMenusSitesService,
    ){}

    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistMenusSitesService->list();
    }
}
