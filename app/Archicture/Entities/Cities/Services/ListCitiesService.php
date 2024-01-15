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
        $listCities = City::query()->with('state');

        if (!empty($params)) {
            $this->getValidate()->validaParametros($params);
            $listCities->when(is_int($params->state_id), function ($query) use ($params) {
                return $query->where('state_id', $params->state_id);
            }, function ($query) use ($params) {
                return $query->whereHas('state', function ($query) use ($params) {
                    return $query->where('acronym', 'like', "%{$params->state}%");
                });
            });
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
