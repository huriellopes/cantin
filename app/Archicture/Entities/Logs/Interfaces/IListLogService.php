<?php

namespace App\Archicture\Entities\Logs\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IListLogService
{
    /**
     * @return Collection
     */
    public function list() : Collection;
}
