<?php

namespace App\Archicture\Entities\Terreiros\Actions;

use App\Archicture\Entities\Terreiros\Interface\IListTerreirosService;
use Illuminate\Database\Eloquent\Collection;

class ListTerreirosAction
{
    /**
     * @param IListTerreirosService $IlistTerreirosService
     */
    public function __construct(
        protected IListTerreirosService $IlistTerreirosService,
    ){}

    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistTerreirosService->list();
    }
}
