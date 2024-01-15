<?php

namespace App\Http\DTO\Terreiro;

use Spatie\LaravelData\Data;

class SearchTerreiroForUFDTO extends Data
{
    /**
     * @param int|null $state_id
     */
    public function __construct(
        public int|null $state_id
    ){}
}
