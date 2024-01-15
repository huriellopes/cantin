<?php

namespace App\Archicture\Entities\Partners\Actions;

use App\Archicture\Entities\Partners\Interface\ICreatePartnersService;
use App\Archicture\Entities\Partners\Models\Partner;
use App\Archicture\Entities\Partners\Validates\PartnersValidate;

class CreatePartnersAction
{
    /**
     * @param ICreatePartnersService $IcreatePartnersService
     */
    public function __construct(
        protected ICreatePartnersService $IcreatePartnersService,
    ){}

    /**
     * @param object $params
     * @return Partner|null
     * @throws \Throwable
     */
    public function execute(object $params) : ?Partner
    {
        $this->getValidate()->validaParametros($params);

        return $this->IcreatePartnersService->create($params);
    }

    /**
     * @return PartnersValidate
     */
    private function getValidate () : PartnersValidate
    {
        return new PartnersValidate();
    }
}
