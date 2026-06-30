<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\StatusPost;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'content' => $this->faker->realText(),
            'main_image' => $this->faker->word(),
            'published_at' => $this->faker->randomElement([Date::now(), Date::now()->addMonth()]),
            'status' => $this->faker->randomElement([StatusPost::PENDING, StatusPost::PUBLISHED]),
            'views' => $this->faker->randomNumber(),
            'user_id' => User::factory(),
            'category_id' => Category::query()->inRandomOrder()?->first()?->id,
            'created_at' => $this->faker->randomElement([Date::now(), Date::now()->addMonth()]),
            'updated_at' => $this->faker->randomElement([Date::now(), Date::now()->addMonth()]),
        ];
    }
}
