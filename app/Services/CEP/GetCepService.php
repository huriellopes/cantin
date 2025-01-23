<?php

namespace App\Services\CEP;

use App\Validates\GetCepValidate;
use App\Traits\Utils;

class GetCepService
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
