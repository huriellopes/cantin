<?php

use App\Enum\Status;
use App\Livewire\Admin\Comments\Index;
use App\Models\Comment;
use Livewire\Livewire;

it('lets an admin open the comments page', function () {
    $this->actingAs(userWithRole('admin'))
        ->get('/admin/comments')
        ->assertOk()
        ->assertSeeLivewire(Index::class);
});

it('publishes a reply to a comment', function () {
    $admin = userWithRole('admin');
    $comment = Comment::factory()->create();

    Livewire::actingAs($admin)->test(Index::class)
        ->call('reply', $comment->id)
        ->set('body', 'Obrigado pelo comentário!')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    expect(Comment::query()
        ->where('parent_id', $comment->id)
        ->where('body', 'Obrigado pelo comentário!')
        ->where('user_id', $admin->id)
        ->exists())->toBeTrue();
});

it('validates the reply body', function () {
    $admin = userWithRole('admin');
    $comment = Comment::factory()->create();

    Livewire::actingAs($admin)->test(Index::class)
        ->call('reply', $comment->id)
        ->set('body', '')
        ->call('save')
        ->assertHasErrors(['body']);
});

it('toggles a comment status', function () {
    $admin = userWithRole('admin');
    $comment = Comment::factory()->create(['status' => Status::ACTIVE]);

    Livewire::actingAs($admin)->test(Index::class)
        ->call('toggleStatus', $comment->id);

    expect($comment->fresh()->status)->toBe(Status::INACTIVE);
});
