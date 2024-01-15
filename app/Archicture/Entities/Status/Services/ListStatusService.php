<?php

namespace App\Archicture\Entities\Status\Services;

use App\Archicture\Entities\Status\Interfaces\IListStatusService;
use App\Archicture\Entities\Status\Models\Status;
use Illuminate\Database\Eloquent\Collection;

class ListStatusService implements IListStatusService
{
    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return Status::query()
            ->select(
                'id',
                'name',
                'description'
            )->get();
    }
}
