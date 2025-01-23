<?php

namespace App\Services\Terreiros;


use App\Models\Terreiro;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchTerreiroForUFService
{
    /**
     * @param object|null $params
     * @return LengthAwarePaginator
     */
    public function search(object $params = null) : LengthAwarePaginator
    {
        $terreiro = Terreiro::query()->with([
            'address:id,address,neighborhood,complement,zipcode,state_id,city_id,number',
            'nation:id,nation',
            'address.state:id,acronym,description',
            'address.city:id,city_name'
        ]);

        if (!empty($params->uf)) {
            $terreiro->with(['address' => function ($query) use ($params) {
                return $query->where('state_id', $params->uf);
            }])->whereHas('address',function ($query) use ($params) {
                return $query->where('state_id', $params->uf);
            });
        }

        return $terreiro->paginate(10);
    }
}
