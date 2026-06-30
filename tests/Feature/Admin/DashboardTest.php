<?php

declare(strict_types=1);

use App\Livewire\Admin\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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
        ->assertSeeLivewire(Dashboard::class);
});
