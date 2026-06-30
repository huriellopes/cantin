<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\Status;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Comment> */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'ip_address' => fake()->ipv4(),
            'post_id' => Post::factory(),
            'parent_id' => null,
            'body' => fake()->sentence(),
            'status' => Status::ACTIVE,
        ];
    }

    /**
     * Marca o comentário como resposta de outro.
     */
    public function replyTo(Comment $parent): static
    {
        return $this->state(fn (): array => [
            'parent_id' => $parent->id,
            'post_id' => $parent->post_id,
        ]);
    }
}
