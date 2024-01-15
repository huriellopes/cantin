<?php

namespace App\Archicture\Entities\Partners\Actions;

use App\Archicture\Entities\Partners\Interface\IUpdatePartnersService;
use App\Archicture\Entities\Partners\Models\Partner;
use App\Archicture\Entities\Partners\Validates\PartnersValidate;

class UpdatePartnersAction
{
    /**
     * @param IUpdatePartnersService $IupdatePatnersService
     */
    public function __construct(
        protected IUpdatePartnersService $IupdatePatnersService,
    ){}

    /**
     * @param Partner $partner
     * @param object $params
     * @return Partner|null
     * @throws \Throwable
     */
    public function execute(Partner $partner, object $params) : ?Partner
    {
        $this->getValidate()->validaParametros($params);

        return $this->IupdatePatnersService->update($partner, $params);
    }

    /**
     * @return PartnersValidate
     */
    private function getValidate () : PartnersValidate
    {
        return new PartnersValidate();
    }
}
