<?php

declare(strict_types=1);

use App\Enum\Role as RoleEnum;
use App\Livewire\Site\Pages\Auth\Register;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;

function ensureUserRole(): void
{
    Role::query()->firstOrCreate(
        ['id' => RoleEnum::USER->value],
        ['slug' => 'user', 'name' => 'User'],
    );
}

it('registers a new user and logs them in', function (): void {
    ensureUserRole();

    Livewire::test(Register::class)
        ->set('name', 'Maria Silva')
        ->set('email', 'maria@example.com')
        ->set('password', 'secret123')
        ->call('store')
        ->assertHasNoErrors()
        ->assertRedirect(route('site.home'));

    expect(User::query()->where('email', 'maria@example.com')->exists())->toBeTrue();
    $this->assertAuthenticated();
});

it('validates the registration fields', function (): void {
    Livewire::test(Register::class)
        ->set('name', '')
        ->set('email', 'nao-eh-email')
        ->set('password', '123')
        ->call('store')
        ->assertHasErrors(['name', 'email', 'password']);
});

it('rejects registration with a duplicate email', function (): void {
    ensureUserRole();
    User::factory()->create(['email' => 'dup@example.com']);

    Livewire::test(Register::class)
        ->set('name', 'Outro')
        ->set('email', 'dup@example.com')
        ->set('password', 'secret123')
        ->call('store')
        ->assertHasErrors(['email']);
});

it('redirects an already authenticated user away from login', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('site.auth.login'))
        ->assertRedirect(route('site.home'));
});
