<?php

namespace App\Archicture\Entities\TypePeoples\Interface;

use Illuminate\Database\Eloquent\Collection;

interface IListTypePeopleService
{
    /**
     * @return Collection
     */
    public function list() : Collection;
}
