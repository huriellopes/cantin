<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\Status;
use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'slug' => $this->faker->unique()->slug(),
            'content' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement([Status::ACTIVE, Status::INACTIVE]),
        ];
    }
}
