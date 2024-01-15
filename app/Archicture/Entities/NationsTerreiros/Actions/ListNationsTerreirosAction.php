<?php

namespace App\Archicture\Entities\NationsTerreiros\Actions;

use App\Archicture\Entities\NationsTerreiros\Interface\IListNationsTerreirosService;
use Illuminate\Database\Eloquent\Collection;

class ListNationsTerreirosAction
{
    /**
     * @param IListNationsTerreirosService $IlistnationsTerreirosService
     */
    public function __construct(
        protected IListNationsTerreirosService $IlistnationsTerreirosService,
    ){}

    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistnationsTerreirosService->list();
    }
}
