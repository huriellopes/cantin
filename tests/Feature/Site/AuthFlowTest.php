<?php

declare(strict_types=1);

use App\Models\User;

it('redirects an already authenticated user away from login', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('site.auth.login'))
        ->assertRedirect(route('site.home'));
});
