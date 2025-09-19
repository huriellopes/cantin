<?php

namespace Database\Factories;

use App\Enum\Status;
use App\Models\StaticPage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StaticPageFactory extends Factory
{
    protected $model = StaticPage::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'slug' => $this->faker->unique()->slug(),
            'content' => $this->faker->unique()->paragraph(),
            'status' => $this->faker->randomElement([Status::ACTIVE, Status::INACTIVE]),
            'user_id' => User::factory(),
        ];
    }
}
