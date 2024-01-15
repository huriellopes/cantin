<?php

namespace App\Archicture\Entities\Partners\Services;

use App\Archicture\Entities\Partners\Interface\ICreatePartnersService;
use App\Archicture\Entities\Partners\Models\Partner;
use App\Archicture\Entities\Status\Enum\StatusEnum;

class CreatePartnersService implements ICreatePartnersService
{

    /**
     * @param object $params
     * @return Partner
     */
    public function create(object $params): Partner
    {
        $patner = new Partner();
        $patner->name = $params->name;
        $patner->email = $params->email;
        $patner->phone = $params->phone;
        $patner->path_image = $params->path_image;
        $patner->status_id = StatusEnum::PENDING->value;

        $patner->save();

        return $patner;
    }
}
