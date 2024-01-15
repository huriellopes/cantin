<?php

namespace App\Archicture\Entities\TypePeoples\Actions;

use App\Archicture\Entities\TypePeoples\Interface\IListTypePeopleService;
use Illuminate\Database\Eloquent\Collection;

class ListTypePeopleAction
{
    /**
     * @param IListTypePeopleService $IlistTypePeopleService
     */
    public function __construct(
        protected IListTypePeopleService $IlistTypePeopleService,
    ){}


    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistTypePeopleService->list();
    }
}
