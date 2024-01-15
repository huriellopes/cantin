<?php

namespace App\Archicture\Entities\Partners\Services;

use App\Archicture\Entities\Partners\Interface\IListPartnersService;
use App\Archicture\Entities\Partners\Models\Partner;
use App\Archicture\Entities\Status\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Collection;

class ListPartnersService implements IListPartnersService
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
