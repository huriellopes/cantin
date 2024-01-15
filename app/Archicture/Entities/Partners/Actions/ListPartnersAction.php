<?php

namespace App\Archicture\Entities\Partners\Actions;

use App\Archicture\Entities\Partners\Interface\IListPartnersService;
use Illuminate\Database\Eloquent\Collection;

class ListPartnersAction
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
        return $this->IlistPartnersService->list();
    }
}
