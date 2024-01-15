<?php

namespace App\Archicture\Entities\Terreiros\Services;

use App\Archicture\Entities\Terreiros\Interface\ISearchTerreiroForUFService;
use App\Archicture\Entities\Terreiros\Models\Terreiro;
use Illuminate\Database\Eloquent\Collection;

class SearchTerreiroForUFService implements ISearchTerreiroForUFService
{
    /**
     * @param object|null $params
     * @return Collection
     */
    public function search(object $params = null): Collection
    {
        $terreiro = Terreiro::query()->with([
            'address:id,address,neighborhood,complement,zipcode,state_id,city_id',
            'nation:id,nation',
            'address.state:id,acronym,description',
            'address.city:id,city_name'
        ]);

        if (!empty($params->state_id)) {
            $terreiro->with(['address' => function ($query) use ($params) {
                return $query->where('state_id', $params->state_id);
            }])->whereHas('address',function ($query) use ($params) {
                return $query->where('state_id', $params->state_id);
            });
        }

        return $terreiro->get();
    }
}
