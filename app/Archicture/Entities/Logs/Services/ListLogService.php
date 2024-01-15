<?php

namespace App\Archicture\Entities\Logs\Services;

use App\Archicture\Entities\Logs\Interfaces\IListLogService;
use App\Archicture\Entities\Logs\Models\Logs;
use Illuminate\Database\Eloquent\Collection;

class ListLogService implements IListLogService
{
    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return Logs::query()->get();
    }
}
