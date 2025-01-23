<?php

namespace App\Services\Logs;

use App\Models\Logs;
use Illuminate\Database\Eloquent\Collection;

class ListLogService
{
    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return Logs::query()
            ->select('action','ip','type','content','user_id')
            ->get();
    }
}
