<?php

namespace App\Http\DTO\PartnersEntities;

use Spatie\LaravelData\Data;

class PartnersEntitiesDTO extends Data
{
    /**
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $activity_carried_out
     * @param string $zipcode
     * @param string $address
     * @param string $number
     * @param string|null $complement
     * @param string $neighborhood
     * @param int $state_id
     * @param int $city_id
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $activity_carried_out,
        public string $zipcode,
        public string $address,
        public string $number,
        public string|null $complement,
        public string $neighborhood,
        public int $state_id,
        public int $city_id,
    ){}
}
