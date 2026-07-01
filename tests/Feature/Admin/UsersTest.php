<?php

declare(strict_types=1);

use App\Enum\Status;
use App\Livewire\Admin\Users\Index;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

it('forbids non super-admins from the users page', function (): void {
    $this->actingAs(userWithRole('admin'))
        ->get('/admin/users')
        ->assertForbidden();
});

it('lets a super-admin open the users page', function (): void {
    $this->actingAs(userWithRole('super-admin'))
        ->get('/admin/users')
        ->assertOk()
        ->assertSeeLivewire(Index::class);
});

it('creates a user through the component', function (): void {
    $this->actingAs(userWithRole('super-admin'));
    $roleId = Role::query()->firstOrCreate(['slug' => 'admin'], ['name' => 'Admin'])->id;

    Livewire::test(Index::class)
        ->call('create')
        ->set('form.name', 'Maria Trans')
        ->set('form.email', 'maria@example.com')
        ->set('form.role_id', $roleId)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    $user = User::query()->where('email', 'maria@example.com')->first();

    expect($user)->not->toBeNull()
        // Senha padrão atribuída e troca obrigatória sinalizada.
        ->and(Hash::check(User::DEFAULT_PASSWORD, $user->password))->toBeTrue()
        ->and($user->password_change_required)->toBeTrue();
});

it('validates required fields on save', function (): void {
    $this->actingAs(userWithRole('super-admin'));

    Livewire::test(Index::class)
        ->call('create')
        ->set('form.name', '')
        ->set('form.email', 'not-an-email')
        ->set('form.role_id')
        ->call('save')
        ->assertHasErrors(['form.name', 'form.email', 'form.role_id']);
});

it('toggles status and refuses to disable yourself', function (): void {
    $admin = userWithRole('super-admin');
    $target = userWithRole('admin');

    Livewire::actingAs($admin)->test(Index::class)
        ->call('toggleStatus', $target->id);
    expect($target->fresh()->status)->toBe(Status::INACTIVE);

    // não pode inativar a si mesmo
    Livewire::actingAs($admin)->test(Index::class)
        ->call('toggleStatus', $admin->id);
    expect($admin->fresh()->status)->toBe(Status::ACTIVE);
});

it('resets a user password to the default and forces a change', function (): void {
    $admin = userWithRole('super-admin');
    $target = userWithRole('admin');

    Livewire::actingAs($admin)->test(Index::class)
        ->call('resetPassword', $target->id)
        ->assertSet('generatedFor', $target->name)
        ->assertSet('generatedPassword', User::DEFAULT_PASSWORD);

    $fresh = $target->fresh();
    expect(Hash::check(User::DEFAULT_PASSWORD, $fresh->password))->toBeTrue()
        ->and($fresh->password_change_required)->toBeTrue();
});
