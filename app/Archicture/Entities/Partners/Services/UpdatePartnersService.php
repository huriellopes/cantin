<?php

namespace App\Archicture\Entities\Partners\Services;

use App\Archicture\Entities\Partners\Interface\IUpdatePartnersService;
use App\Archicture\Entities\Partners\Models\Partner;

class UpdatePartnersService implements IUpdatePartnersService
{

    /**
     * @param Partner $partner
     * @param object $params
     * @return Partner|null
     */
    public function update(Partner $partner, object $params): ?Partner
    {
        $searchPatner = Partner::query()->where('id', '=', $partner->id)->first();

        $searchPatner->name = $params->name;
        $searchPatner->email = $params->email;
        $searchPatner->phone = $params->phone;
        $searchPatner->path_image = $params->path_image;
        $searchPatner->status_id = $params->status_id;

        $searchPatner->save();

        $searchPatner->refresh();

        return $searchPatner;
    }
}
