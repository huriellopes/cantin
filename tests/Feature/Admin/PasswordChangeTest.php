<?php

declare(strict_types=1);

use App\Livewire\Admin\PasswordChange;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

function userNeedingPasswordChange(): User
{
    $user = userWithRole('admin');
    $user->fill([
        'password' => User::DEFAULT_PASSWORD,
        'password_change_required' => true,
    ])->save();

    return $user;
}

it('forces a user with the default password to the change-password page on login', function (): void {
    $user = userNeedingPasswordChange();

    $this->post(route('site.auth.login.post'), [
        'email' => $user->email,
        'password' => User::DEFAULT_PASSWORD,
    ])->assertRedirect(route('admin.password.change'));
});

it('redirects to the change-password page while the flag is set (middleware)', function (): void {
    $this->actingAs(userNeedingPasswordChange())
        ->get(route('admin.dashboard'))
        ->assertRedirect(route('admin.password.change'));
});

it('lets the user reach the change-password page itself', function (): void {
    $this->actingAs(userNeedingPasswordChange())
        ->get(route('admin.password.change'))
        ->assertOk();
});

it('changes the password, clears the flag and redirects to the dashboard', function (): void {
    $user = userNeedingPasswordChange();

    Livewire::actingAs($user)->test(PasswordChange::class)
        ->set('password', 'NovaSenhaForte1')
        ->set('password_confirmation', 'NovaSenhaForte1')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.dashboard'));

    $fresh = $user->fresh();
    expect($fresh->password_change_required)->toBeFalse()
        ->and(Hash::check('NovaSenhaForte1', $fresh->password))->toBeTrue();
});

it('rejects keeping the default password', function (): void {
    Livewire::actingAs(userNeedingPasswordChange())->test(PasswordChange::class)
        ->set('password', User::DEFAULT_PASSWORD)
        ->set('password_confirmation', User::DEFAULT_PASSWORD)
        ->call('save')
        ->assertHasErrors('password');
});

it('requires the confirmation to match', function (): void {
    Livewire::actingAs(userNeedingPasswordChange())->test(PasswordChange::class)
        ->set('password', 'NovaSenhaForte1')
        ->set('password_confirmation', 'diferente')
        ->call('save')
        ->assertHasErrors('password');
});
