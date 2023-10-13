<?php

namespace App\Http\DTO\Cities;

use Spatie\LaravelData\Data;

class ListCitiesDTO extends Data
{
    /**
     * @param int|null $state_id
     */
    public function __construct(
        public int|null $state_id,
    ){}
}
