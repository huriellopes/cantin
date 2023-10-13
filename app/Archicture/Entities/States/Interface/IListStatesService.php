<?php

namespace App\Archicture\Entities\States\Interface;

use Illuminate\Database\Eloquent\Collection;

interface IListStatesService
{
    /**
     * @return Collection
     */
    public function list() : Collection;
}
