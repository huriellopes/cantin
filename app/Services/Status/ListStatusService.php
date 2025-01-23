<?php

namespace App\Services\Status;

use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;

class ListStatusService
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
