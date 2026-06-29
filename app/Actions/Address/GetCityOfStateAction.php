<?php

namespace App\Actions\Address;

use App\Models\City;
use Illuminate\Support\Collection;

final class GetCityOfStateAction
{
    public static function handle(int $stateId): Collection
    {
        return City::query()
            ->where('state_id', '=', $stateId)
            ->get()
            ->pluck('name', 'id');
    }
}
