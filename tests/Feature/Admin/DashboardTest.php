<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function userWithRole(string $slug): User
{
    $role = Role::query()->firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug)]);

    return User::factory()->create(['role_id' => $role->id]);
}

it('redirects guests to the site login', function () {
    $this->get('/admin')->assertRedirect(route('site.auth.login'));
});

it('forbids regular users from the admin panel', function () {
    $this->actingAs(userWithRole('user'))
        ->get('/admin')
        ->assertForbidden();
});

it('renders the dashboard for an admin', function () {
    $this->actingAs(userWithRole('super-admin'))
        ->get('/admin')
        ->assertOk()
        ->assertSee('Painel')
        ->assertSeeLivewire(App\Livewire\Admin\Dashboard::class);
});
