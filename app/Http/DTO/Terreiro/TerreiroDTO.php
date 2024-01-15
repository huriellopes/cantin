<?php

namespace App\Http\DTO\Terreiro;

use Spatie\LaravelData\Data;

class TerreiroDTO extends Data
{
    /**
     * @param string $name
     * @param string $phone
     * @param string $fundationed_at
     * @param int $nation_terreiro_id
     * @param string $leadership_orunko
     * @param string $color_of_leadership
     * @param string $zipcode
     * @param string $address
     * @param string|null $complement
     * @param string $number
     * @param string $neighborhood
     * @param int $state_id
     * @param int $city_id
     */
    public function __construct(
        public string $name,
        public string $phone,
        public string $fundationed_at,
        public int $nation_terreiro_id,
        public string $leadership_orunko,
        public string $color_of_leadership,
        public string $zipcode,
        public string $address,
        public string|null $complement,
        public string $number,
        public string $neighborhood,
        public int $state_id,
        public int $city_id,
    ){}
}
