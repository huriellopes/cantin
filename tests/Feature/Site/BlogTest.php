<?php

declare(strict_types=1);

use App\Enum\StatusPost;
use App\Livewire\Site\Pages\Blog\Posts;
use App\Livewire\Site\Pages\Blog\Show;
use App\Models\Dislike;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;

function publishedPost(array $attrs = []): Post
{
    return Post::factory()->create(array_merge([
        'status' => StatusPost::PUBLISHED,
        'published_at' => now()->subDay(),
    ], $attrs));
}

it('lists only published posts on the blog', function (): void {
    $published = publishedPost(['title' => 'Publicado visível']);
    $pending = Post::factory()->create(['title' => 'Rascunho oculto', 'status' => StatusPost::PENDING]);

    Livewire::test(Posts::class)
        ->assertSee('Publicado visível')
        ->assertDontSee('Rascunho oculto');
});

it('filters posts by search term', function (): void {
    publishedPost(['title' => 'Axé e acolhimento']);
    publishedPost(['title' => 'Outro assunto qualquer']);

    Livewire::test(Posts::class)
        ->set('search', 'Axé')
        ->assertSee('Axé e acolhimento')
        ->assertDontSee('Outro assunto qualquer');
});

it('lets an authenticated user like and unlike a post', function (): void {
    $user = User::factory()->create();
    $post = publishedPost();

    $component = Livewire::actingAs($user)->test(Show::class, ['post' => $post]);

    $component->call('like')->assertSet('userLiked', true);
    expect(Like::query()->where('post_id', $post->id)->count())->toBe(1);

    $component->call('like')->assertSet('userLiked', false);
    expect(Like::query()->where('post_id', $post->id)->count())->toBe(0);
});

it('replaces a like with a dislike (mutually exclusive)', function (): void {
    $user = User::factory()->create();
    $post = publishedPost();

    $component = Livewire::actingAs($user)->test(Show::class, ['post' => $post]);

    $component->call('like')->assertSet('userLiked', true);
    $component->call('dislike')
        ->assertSet('userDisliked', true)
        ->assertSet('userLiked', false);

    expect(Like::query()->where('post_id', $post->id)->count())->toBe(0)
        ->and(Dislike::query()->where('post_id', $post->id)->count())->toBe(1);
});

it('lets a guest like a post (tracked by IP)', function (): void {
    $post = publishedPost();

    Livewire::test(Show::class, ['post' => $post])->call('like');

    expect(Like::query()->where('post_id', $post->id)->count())->toBe(1);
});
