<?php

declare(strict_types=1);

use App\Livewire\Admin\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests to the site login', function (): void {
    $this->get('/admin')->assertRedirect(route('site.auth.login'));
});

it('renders the dashboard for an admin', function (): void {
    $this->actingAs(userWithRole('super-admin'))
        ->get('/admin')
        ->assertOk()
        ->assertSee('Painel')
        ->assertSeeLivewire(Dashboard::class);
});
