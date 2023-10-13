<?php

namespace App\Archicture\Entities\CEP\Actions;

use App\Archicture\Entities\CEP\Interfaces\IGetCepService;

class GetCepAction
{
    /**
     * @param IGetCepService $IgetCepService
     */
    public function __construct(
        protected IGetCepService $IgetCepService,
    ){}

    public function execute(object $params): array
    {
        return $this->IgetCepService->getCep($params);
    }
}
