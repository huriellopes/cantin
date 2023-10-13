<?php

namespace App\Archicture\Entities\Cities\Interface;

use Illuminate\Database\Eloquent\Collection;

interface IListCitiesService
{
    /**
     * @param object|null $params
     * @return Collection
     */
    public function list(object $params = null) : Collection;
}
