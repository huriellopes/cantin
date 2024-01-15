<?php

namespace App\Archicture\Entities\Terreiros\Actions;

use App\Archicture\Entities\Terreiros\Interface\ICreateTerreirosService;
use App\Archicture\Entities\Terreiros\Models\Terreiro;
use App\Archicture\Entities\Terreiros\Validates\TerreiroValidate;

class CreateTerreirosAction
{
    /**
     * @param ICreateTerreirosService $IcreateTerreirosService
     */
    public function __construct(
        protected ICreateTerreirosService $IcreateTerreirosService,
    ){}

    /**
     * @param object $params
     * @return Terreiro
     * @throws \Throwable
     */
    public function execute(object $params) : Terreiro
    {
        $this->getValidate()->validaParametros($params);
        return $this->IcreateTerreirosService->create($params);
    }

    /**
     * @return TerreiroValidate
     */
    private function getValidate() : TerreiroValidate
    {
        return new TerreiroValidate();
    }
}
