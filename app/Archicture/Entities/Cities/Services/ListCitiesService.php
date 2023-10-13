<?php

namespace App\Archicture\Entities\Cities\Services;

use App\Archicture\Entities\Cities\Interface\IListCitiesService;
use App\Archicture\Entities\Cities\Models\City;
use App\Archicture\Entities\Cities\Validates\ListCitiesValidate;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class ListCitiesService implements IListCitiesService
{
    /**
     * @param object|null $params
     * @return Collection
     * @throws Throwable
     */
    public function list(object $params = null): Collection
    {
        $listCities = City::with('state');

        if (!empty($params)) {
            $this->getValidate()->validaParametros($params);
            $listCities->where('state_id', $params->state_id);
        }

        return $listCities->get();
    }

    /**
     * @return ListCitiesValidate
     */
    private function getValidate() : ListCitiesValidate
    {
        return new ListCitiesValidate();
    }
}
