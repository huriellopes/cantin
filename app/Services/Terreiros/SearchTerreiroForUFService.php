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
    public static function search(object $params = null) : LengthAwarePaginator
    {
        $terreiro = Terreiro::query()
            ->select('id',
                'name',
                'phone',
                'fundationed_at',
                'nation_terreiro_id',
                'leadership_orunko',
                'color_of_leadership',
                'address_id',
                'created_at',
                'updated_at');

        if (!empty($params->uf)) {
            $terreiro->whereHas('address',function ($query) use ($params) {
                return $query->where('state_id', $params->uf);
            });
        }

        return $terreiro->paginate(10);
    }
}
