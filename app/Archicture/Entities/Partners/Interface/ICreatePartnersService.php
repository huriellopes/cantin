<?php

namespace App\Archicture\Entities\Partners\Interface;

use App\Archicture\Entities\Partners\Models\Partner;

interface ICreatePartnersService
{
    /**
     * @param object $params
     * @return Partner
     */
    public function create(object $params) : Partner;
}
