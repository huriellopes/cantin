<?php

use App\Enum\Status;
use App\Livewire\Admin\Users\Index;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;

it('forbids non super-admins from the users page', function () {
    $this->actingAs(userWithRole('admin'))
        ->get('/admin/users')
        ->assertForbidden();
});

it('lets a super-admin open the users page', function () {
    $this->actingAs(userWithRole('super-admin'))
        ->get('/admin/users')
        ->assertOk()
        ->assertSeeLivewire(Index::class);
});

it('creates a user through the component', function () {
    $this->actingAs(userWithRole('super-admin'));
    $roleId = Role::query()->firstOrCreate(['slug' => 'user'], ['name' => 'User'])->id;

    Livewire::test(Index::class)
        ->call('create')
        ->set('name', 'Maria Trans')
        ->set('email', 'maria@example.com')
        ->set('role_id', $roleId)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    expect(User::query()->where('email', 'maria@example.com')->exists())->toBeTrue();
});

it('validates required fields on save', function () {
    $this->actingAs(userWithRole('super-admin'));

    Livewire::test(Index::class)
        ->call('create')
        ->set('name', '')
        ->set('email', 'not-an-email')
        ->set('role_id', null)
        ->call('save')
        ->assertHasErrors(['name', 'email', 'role_id']);
});

it('toggles status and refuses to disable yourself', function () {
    $admin = userWithRole('super-admin');
    $target = userWithRole('user');

    Livewire::actingAs($admin)->test(Index::class)
        ->call('toggleStatus', $target->id);
    expect($target->fresh()->status)->toBe(Status::INACTIVE);

    // não pode inativar a si mesmo
    Livewire::actingAs($admin)->test(Index::class)
        ->call('toggleStatus', $admin->id);
    expect($admin->fresh()->status)->toBe(Status::ACTIVE);
});

it('resets a user password', function () {
    $admin = userWithRole('super-admin');
    $target = userWithRole('user');
    $oldHash = $target->password;

    Livewire::actingAs($admin)->test(Index::class)
        ->call('resetPassword', $target->id)
        ->assertSet('generatedFor', $target->name);

    expect($target->fresh()->password)->not->toBe($oldHash);
});
