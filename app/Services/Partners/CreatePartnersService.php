<?php

namespace App\Services\Partners;

use App\Models\Partner;
use App\Enum\StatusEnum;

class CreatePartnersService
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
