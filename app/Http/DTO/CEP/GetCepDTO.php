<?php

namespace App\Http\DTO\CEP;

use Spatie\LaravelData\Data;

class GetCepDTO extends Data
{
    /**
     * @param string $zipcode
     */
    public function __construct(
        public string $zipcode,
    ){}
}
