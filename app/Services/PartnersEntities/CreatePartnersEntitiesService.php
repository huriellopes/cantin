<?php

namespace App\Services\PartnersEntities;

use App\Models\ParternEntity;

class CreatePartnersEntitiesService
{

    /**
     * @param object $params
     * @return ParternEntity
     */
    public function create(object $params): ParternEntity
    {
        $partnersEntities = new ParternEntity();
        $partnersEntities->name = $params->name;
        $partnersEntities->email = $params->email;
        $partnersEntities->phone = $params->phone;
        $partnersEntities->address_id = $params->address_id;
        $partnersEntities->activity_carried_out = $params->activity_carried_out;

        $partnersEntities->save();

        return $partnersEntities;
    }
}
