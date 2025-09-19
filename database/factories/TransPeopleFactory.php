<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\TransPeople;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransPeople> */
class TransPeopleFactory extends Factory
{
    protected $model = TransPeople::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address_id' => Address::factory(),
        ];
    }
}
