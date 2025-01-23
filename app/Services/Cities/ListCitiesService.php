<?php

namespace App\Services\Cities;

use App\Models\City;
use App\Validates\ListCitiesValidate;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class ListCitiesService
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
