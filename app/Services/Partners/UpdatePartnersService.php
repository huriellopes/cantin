<?php

namespace App\Services\Partners;

use App\Models\Partner;

class UpdatePartnersService
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
