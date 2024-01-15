<?php

namespace App\Archicture\Entities\PartnersEntities\Services;

use App\Archicture\Entities\PartnersEntities\Interfaces\ICreatePartnersEntitiesService;
use App\Archicture\Entities\PartnersEntities\Models\ParternEntity;
use App\Archicture\Generics\TraitsGenerals\Helpers;

class CreatePartnersEntitiesService implements ICreatePartnersEntitiesService
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
