<?php

declare(strict_types=1);

use App\Enum\Status;
use App\Enum\StatusPost;
use App\Livewire\Admin\Posts\Index;
use App\Models\Category;
use App\Models\Post;
use Livewire\Livewire;

function aCategory(): Category
{
    return Category::query()->create(['name' => 'Geral', 'slug' => 'geral-' . uniqid(), 'status' => Status::ACTIVE]);
}

it('lets an admin open the posts page', function (): void {
    $this->actingAs(userWithRole('admin'))
        ->get('/admin/posts')
        ->assertOk()
        ->assertSeeLivewire(Index::class);
});

it('creates a post and publishes it when the date is today', function (): void {
    $admin = userWithRole('admin');
    $category = aCategory();

    Livewire::actingAs($admin)->test(Index::class)
        ->call('create')
        ->set('titleField', 'Meu primeiro post')
        ->set('slug', '')
        ->set('category_id', $category->id)
        ->set('published_at', now()->format('Y-m-d'))
        ->set('content', 'Conteúdo do post.')
        ->call('save')
        ->assertHasNoErrors();

    $post = Post::query()->where('slug', 'meu-primeiro-post')->first();
    expect($post)->not->toBeNull()
        ->and($post->status)->toBe(StatusPost::PUBLISHED)
        ->and($post->user_id)->toBe($admin->id);
});

it('keeps a future post pending', function (): void {
    $admin = userWithRole('admin');
    $category = aCategory();

    Livewire::actingAs($admin)->test(Index::class)
        ->call('create')
        ->set('titleField', 'Post futuro')
        ->set('category_id', $category->id)
        ->set('published_at', now()->addWeek()->format('Y-m-d'))
        ->set('content', 'Em breve.')
        ->call('save')
        ->assertHasNoErrors();

    expect(Post::query()->where('slug', 'post-futuro')->value('status'))->toBe(StatusPost::PENDING);
});

it('publishes and unpublishes a post', function (): void {
    $admin = userWithRole('admin');
    $post = Post::factory()->create(['status' => StatusPost::PENDING, 'published_at' => now()->subDay()]);

    Livewire::actingAs($admin)->test(Index::class)->call('publish', $post->id);
    expect($post->fresh()->status)->toBe(StatusPost::PUBLISHED);

    Livewire::actingAs($admin)->test(Index::class)->call('unpublish', $post->id);
    expect($post->fresh()->status)->toBe(StatusPost::PENDING);
});

it('validates required post fields', function (): void {
    Livewire::actingAs(userWithRole('admin'))->test(Index::class)
        ->call('create')
        ->set('titleField', '')
        ->set('category_id')
        ->set('content', '')
        ->call('save')
        ->assertHasErrors(['titleField', 'category_id', 'content']);
});
