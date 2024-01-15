<?php

namespace App\Http\DTO\Partners;

use Spatie\LaravelData\Data;

class PartnersDTO extends Data
{
    /**
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $path_image
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $path_image,
    ){}
}
