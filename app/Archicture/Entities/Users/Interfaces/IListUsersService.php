<?php

namespace App\Archicture\Entities\Users\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IListUsersService
{
    /**
     * @return Collection
     */
    public function list() : Collection;
}
