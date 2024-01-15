<?php

namespace App\Archicture\Entities\Partners\Interface;

use App\Archicture\Entities\Partners\Models\Partner;

interface IUpdatePartnersService
{
    /**
     * @param Partner $partner
     * @param object $params
     * @return Partner|null
     */
    public function update(Partner $partner, object $params) : ?Partner;
}
