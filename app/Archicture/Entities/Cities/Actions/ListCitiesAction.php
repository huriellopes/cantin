<?php

namespace App\Archicture\Entities\Cities\Actions;

use App\Archicture\Entities\Cities\Interface\IListCitiesService;
use Illuminate\Database\Eloquent\Collection;

class ListCitiesAction
{
    /**
     * @param IListCitiesService $IlistCitiesService
     */
    public function __construct(
        protected IListCitiesService $IlistCitiesService,
    ){}

    /**
     * @param object|null $params
     * @return Collection
     */
    public function execute(object $params = null) : Collection
    {
        return $this->IlistCitiesService->list($params);
    }
}
