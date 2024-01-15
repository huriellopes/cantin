<?php

namespace App\Archicture\Entities\Status\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IListStatusService
{
    /**
     * @return Collection
     */
    public function list() : Collection;
}
