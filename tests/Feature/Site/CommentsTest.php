<?php

declare(strict_types=1);

use App\Enum\StatusPost;
use App\Livewire\Site\Components\Blog\Comments;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;

function postForComments(): Post
{
    return Post::factory()->create([
        'status' => StatusPost::PUBLISHED,
        'published_at' => now()->subDay(),
    ]);
}

it('lets an authenticated user post a comment', function (): void {
    $user = User::factory()->create();
    $post = postForComments();

    Livewire::actingAs($user)->test(Comments::class, ['post' => $post])
        ->set('newComment', 'Excelente conteúdo, obrigado!')
        ->call('store')
        ->assertHasNoErrors();

    expect(Comment::query()
        ->where('post_id', $post->id)
        ->where('user_id', $user->id)
        ->where('body', 'Excelente conteúdo, obrigado!')
        ->exists())->toBeTrue();
});

it('lets a guest post a comment with name and email', function (): void {
    $post = postForComments();

    Livewire::test(Comments::class, ['post' => $post])
        ->set('name', 'Visitante')
        ->set('email', 'visitante@example.com')
        ->set('newComment', 'Comentário anônimo de teste')
        ->call('store')
        ->assertHasNoErrors();

    $comment = Comment::query()->where('post_id', $post->id)->first();

    expect($comment)->not->toBeNull()
        ->and($comment->user_id)->toBeNull()
        ->and($comment->name)->toBe('Visitante')
        ->and($comment->body)->toBe('Comentário anônimo de teste');
});
