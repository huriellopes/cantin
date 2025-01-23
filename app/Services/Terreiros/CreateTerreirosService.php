<?php

namespace App\Services\Terreiros;

use App\Models\Terreiro;

class CreateTerreirosService
{

    /**
     * @param object $params
     * @return Terreiro
     */
    public function create(object $params): Terreiro
    {
        $terreiro = new Terreiro();
        $terreiro->name = $params->name;
        $terreiro->phone = $params->phone;
        $terreiro->fundationed_at = $params->fundationed_at;
        $terreiro->nation_terreiro_id = $params->nation_terreiro_id;
        $terreiro->leadership_orunko = $params->leadership_orunko;
        $terreiro->color_of_leadership = $params->color_of_leadership;
        $terreiro->address_id = $params->address_id;

        $terreiro->save();

        return $terreiro;
    }
}
