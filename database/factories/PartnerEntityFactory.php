<?php

namespace Database\Factories;

use App\Enum\Status;
use App\Models\Address;
use App\Models\PartnerEntity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PartnerEntity> */
class PartnerEntityFactory extends Factory
{
    protected $model = PartnerEntity::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'activity_carried_out' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'path_image' => $this->faker->word(),
            'user_id' => $this->faker->randomElement([User::factory(), null]),
            'status' => $this->faker->randomElement([Status::ACTIVE, Status::INACTIVE]),
            'address_id' => Address::factory(),
        ];
    }
}
