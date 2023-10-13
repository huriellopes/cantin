<?php

namespace App\Archicture\Entities\CEP\Interfaces;

interface IGetCepService
{
    /**
     * @param object $params
     * @return array
     */
    public function getCep(object $params) : array;
}
