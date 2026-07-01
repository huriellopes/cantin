<?php

declare(strict_types=1);

use App\Enum\Status;
use App\Enum\StatusPost;
use App\Livewire\Admin\Posts\Index;
use App\Livewire\Admin\Posts\Manage;
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

    Livewire::actingAs($admin)->test(Manage::class)
        ->set('form.titleField', 'Meu primeiro post')
        ->set('form.slug', '')
        ->set('form.category_id', $category->id)
        ->set('form.published_at', now()->format('Y-m-d'))
        ->set('form.content', '<p>Conteúdo do post.</p>')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.posts.index'));

    $post = Post::query()->where('slug', 'meu-primeiro-post')->first();
    expect($post)->not->toBeNull()
        ->and($post->status)->toBe(StatusPost::PUBLISHED)
        ->and($post->user_id)->toBe($admin->id)
        ->and($post->content)->toBe('<p>Conteúdo do post.</p>');
});

it('keeps a future post pending', function (): void {
    $admin = userWithRole('admin');
    $category = aCategory();

    Livewire::actingAs($admin)->test(Manage::class)
        ->set('form.titleField', 'Post futuro')
        ->set('form.category_id', $category->id)
        ->set('form.published_at', now()->addWeek()->format('Y-m-d'))
        ->set('form.content', 'Em breve.')
        ->call('save')
        ->assertHasNoErrors();

    expect(Post::query()->where('slug', 'post-futuro')->value('status'))->toBe(StatusPost::PENDING);
});

it('edits an existing post through the manage page', function (): void {
    $admin = userWithRole('admin');
    $post = Post::factory()->create(['title' => 'Antigo', 'content' => '<p>velho</p>', 'category_id' => aCategory()->id]);

    Livewire::actingAs($admin)->test(Manage::class, ['post' => $post])
        ->assertSet('form.titleField', 'Antigo')
        ->set('form.titleField', 'Atualizado')
        ->set('form.content', '<p>novo</p>')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.posts.index'));

    expect($post->fresh())
        ->title->toBe('Atualizado')
        ->content->toBe('<p>novo</p>');
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
    Livewire::actingAs(userWithRole('admin'))->test(Manage::class)
        ->set('form.titleField', '')
        ->set('form.category_id')
        ->set('form.content', '')
        ->call('save')
        ->assertHasErrors(['form.titleField', 'form.category_id', 'form.content']);
});
