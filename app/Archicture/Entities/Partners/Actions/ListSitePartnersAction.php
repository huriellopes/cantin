<?php

namespace App\Archicture\Entities\Partners\Actions;

use App\Archicture\Entities\Partners\Interface\IListPartnersService;
use Illuminate\Database\Eloquent\Collection;

class ListSitePartnersAction
{
    /**
     * @param IListPartnersService $IlistPartnersService
     */
    public function __construct(
        protected IListPartnersService $IlistPartnersService,
    ){}

    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistPartnersService->list(1);
    }
}
