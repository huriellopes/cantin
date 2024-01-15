<?php

namespace App\Archicture\Entities\Partners\Interface;

use Illuminate\Database\Eloquent\Collection;

interface IListPartnersService
{
    /**
     * @param int $status
     * @return Collection
     */
    public function list(int $status = 2) : Collection;
}
