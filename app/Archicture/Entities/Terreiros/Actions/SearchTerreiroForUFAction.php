<?php

namespace App\Archicture\Entities\Terreiros\Actions;

use App\Archicture\Entities\Terreiros\Interface\ISearchTerreiroForUFService;
use Illuminate\Database\Eloquent\Collection;

class SearchTerreiroForUFAction
{
    /**
     * @param ISearchTerreiroForUFService $IsearchTerreiroForUFService
     */
    public function __construct(
        protected ISearchTerreiroForUFService $IsearchTerreiroForUFService,
    ){}

    /**
     * @param object|null $params
     * @return Collection
     */
    public function execute(object $params = null): Collection
    {
        return $this->IsearchTerreiroForUFService->search($params);
    }
}
