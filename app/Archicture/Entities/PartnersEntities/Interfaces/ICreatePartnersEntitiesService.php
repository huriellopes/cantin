<?php

namespace App\Archicture\Entities\PartnersEntities\Interfaces;

use App\Archicture\Entities\PartnersEntities\Models\ParternEntity;

interface ICreatePartnersEntitiesService
{
    /**
     * @param object $params
     * @return ParternEntity
     */
    public function create(object $params) : ParternEntity;
}
