<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Dislike;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Dislike> */
class DislikeFactory extends Factory
{
    protected $model = Dislike::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'ip_address' => $this->faker->ipv4(),
            'comment_id' => Comment::factory(),
            'post_id' => Post::factory(),
        ];
    }
}
