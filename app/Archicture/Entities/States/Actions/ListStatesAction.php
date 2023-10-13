<?php

namespace App\Archicture\Entities\States\Actions;

use App\Archicture\Entities\States\Interface\IListStatesService;
use Illuminate\Database\Eloquent\Collection;

class ListStatesAction
{
    /**
     * @param IListStatesService $IlistStatesService
     */
    public function __construct(
        protected IListStatesService $IlistStatesService,
    ){}

    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistStatesService->list();
    }
}
