<?php

namespace App\Archicture\Entities\CEP\Services;

use App\Archicture\Entities\CEP\Interfaces\IGetCepService;
use App\Archicture\Entities\CEP\Validate\GetCepValidate;
use App\Traits\Utils;

class GetCepService implements IGetCepService
{
    use Utils;

    /**
     * @param object $params
     * @return array
     * @throws \Throwable
     */
    public function getCep(object $params): array
    {
        $this->getValidate()->validaParametros($params);
        $zipcode = $this->clearMask($params->zipcode);

        return $this->consultAPI(config('services.viacep.endpoint'),$zipcode.'/json/', null);
    }

    private function getValidate() : GetCepValidate
    {
        return new GetCepValidate;
    }
}
