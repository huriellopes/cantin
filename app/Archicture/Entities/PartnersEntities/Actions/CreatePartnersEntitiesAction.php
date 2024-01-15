<?php

namespace App\Archicture\Entities\PartnersEntities\Actions;

use App\Archicture\Entities\PartnersEntities\Interfaces\ICreatePartnersEntitiesService;
use App\Archicture\Entities\PartnersEntities\Models\ParternEntity;

class CreatePartnersEntitiesAction
{
    /**
     * @param ICreatePartnersEntitiesService $IcreatePartnersEntitiesService
     */
    public function __construct(
        protected ICreatePartnersEntitiesService $IcreatePartnersEntitiesService,
    ){}

    /**
     * @param object $params
     * @return ParternEntity
     */
    public function execute(object $params) : ParternEntity
    {
        return $this->IcreatePartnersEntitiesService->create($params);
    }
}
