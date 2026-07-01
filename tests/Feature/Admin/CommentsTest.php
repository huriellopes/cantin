<?php

declare(strict_types=1);

use App\Enum\Status;
use App\Livewire\Admin\Comments\Index;
use App\Models\Comment;
use Livewire\Livewire;

it('lets an admin open the comments page', function (): void {
    $this->actingAs(userWithRole('admin'))
        ->get('/admin/comments')
        ->assertOk()
        ->assertSeeLivewire(Index::class);
});

it('publishes a reply to a comment', function (): void {
    $admin = userWithRole('admin');
    $comment = Comment::factory()->create();

    Livewire::actingAs($admin)->test(Index::class)
        ->call('reply', $comment->id)
        ->set('form.body', 'Obrigado pelo comentário!')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    expect(Comment::query()
        ->where('parent_id', $comment->id)
        ->where('body', 'Obrigado pelo comentário!')
        ->where('user_id', $admin->id)
        ->exists())->toBeTrue();
});

it('validates the reply body', function (): void {
    $admin = userWithRole('admin');
    $comment = Comment::factory()->create();

    Livewire::actingAs($admin)->test(Index::class)
        ->call('reply', $comment->id)
        ->set('form.body', '')
        ->call('save')
        ->assertHasErrors(['form.body']);
});

it('toggles a comment status', function (): void {
    $admin = userWithRole('admin');
    $comment = Comment::factory()->create(['status' => Status::ACTIVE]);

    Livewire::actingAs($admin)->test(Index::class)
        ->call('toggleStatus', $comment->id);

    expect($comment->fresh()->status)->toBe(Status::INACTIVE);
});
