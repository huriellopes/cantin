<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address> */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'zipcode' => $this->faker->numberBetween(10000000, 99999999),
            'address' => $this->faker->address(),
            'complement' => $this->faker->word(),
            'neighborhood' => $this->faker->city,
            'state_id' => State::query()->inRandomOrder()->first()->id,
            'city_id' => City::query()->inRandomOrder()->first()->id,
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }
}
