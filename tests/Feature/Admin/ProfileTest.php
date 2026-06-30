<?php

declare(strict_types=1);

use App\Livewire\Admin\Profile\Index;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

it('updates the authenticated user profile', function () {
    $user = userWithRole('super-admin');

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('name', 'Novo Nome')
        ->set('email', 'novo@cantin.test')
        ->call('updateProfile')
        ->assertHasNoErrors();

    expect($user->fresh()->name)->toBe('Novo Nome')
        ->and($user->fresh()->email)->toBe('novo@cantin.test');
});

it('changes the password with the correct current password', function () {
    $user = userWithRole('super-admin');

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('current_password', 'password')
        ->set('password', 'nova-senha-123')
        ->set('password_confirmation', 'nova-senha-123')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(Hash::check('nova-senha-123', $user->fresh()->password))->toBeTrue();
});

it('rejects a password change with a wrong current password', function () {
    $user = userWithRole('super-admin');

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('current_password', 'senha-errada')
        ->set('password', 'nova-senha-123')
        ->set('password_confirmation', 'nova-senha-123')
        ->call('updatePassword')
        ->assertHasErrors('current_password');
});

it('deletes the account only with the correct password', function () {
    $user = userWithRole('super-admin');

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('delete_password', 'password')
        ->call('deleteAccount')
        ->assertRedirect(route('site.home'));

    expect(User::query()->whereKey($user->id)->exists())->toBeFalse();
    expect(auth()->check())->toBeFalse();
});

it('does not delete the account with a wrong password', function () {
    $user = userWithRole('super-admin');

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('delete_password', 'senha-errada')
        ->call('deleteAccount')
        ->assertHasErrors('delete_password');

    expect(User::query()->whereKey($user->id)->exists())->toBeTrue();
});
