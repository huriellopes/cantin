<?php

namespace App\Http\DTO\Cities;

use Spatie\LaravelData\Data;

class ListCitiesDTO extends Data
{
    /**
     * @param int|null $state_id
     * @param string|null $state
     */
    public function __construct(
        public int|null $state_id,
        public string|null $state,
    ){}
}
