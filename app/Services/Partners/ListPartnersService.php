<?php

namespace App\Services\Partners;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Collection;

class ListPartnersService
{

    /**
     * @param int $status
     * @return Collection
     */
    public function list(int $status = 2): Collection
    {
        return Partner::query()->with(['status' => function ($query) use ($status){
            return $query->where('id', '=', $status);
        }, 'user'])->whereHas('status', function ($query) use ($status) {
            return $query->where('status_id', '=', $status);
        })->get();
    }
}
