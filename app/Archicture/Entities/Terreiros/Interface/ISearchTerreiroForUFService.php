<?php

namespace App\Archicture\Entities\Terreiros\Interface;

use Illuminate\Database\Eloquent\Collection;

interface ISearchTerreiroForUFService
{
    /**
     * @param object|null $params
     * @return Collection
     */
    public function search(object $params = null): Collection;
}
