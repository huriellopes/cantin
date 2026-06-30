<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\Status;
use App\Models\ExternalLink;
use App\Models\TypeExternalLink;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ExternalLink> */
class ExternalLinkFactory extends Factory
{
    protected $model = ExternalLink::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->text(),
            'url' => $this->faker->url(),
            'status' => $this->faker->randomElement(Status::cases()),
            'user_id' => User::factory(),
            'type_external_link_id' => TypeExternalLink::query()->inRandomOrder()->first()?->id,
        ];
    }
}
