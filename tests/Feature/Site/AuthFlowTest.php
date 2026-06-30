<?php

declare(strict_types=1);

use App\Models\User;

it('redirects an already authenticated user away from login', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('site.auth.login'))
        ->assertRedirect(route('site.home'));
});

it('rate limits repeated failed login attempts', function (): void {
    $user = userWithRole('admin');

    // 5 tentativas com senha errada → erro de credenciais ('message').
    for ($i = 0; $i < 5; $i++) {
        $this->post(route('site.auth.login.post'), [
            'email' => $user->email,
            'password' => 'senha-errada',
        ])->assertSessionHasErrors('message');
    }

    // 6ª tentativa → bloqueada pelo throttle (erro no campo 'email').
    $this->post(route('site.auth.login.post'), [
        'email' => $user->email,
        'password' => 'senha-errada',
    ])->assertSessionHasErrors('email');
});

it('records the last login timestamp on a successful login', function (): void {
    $user = userWithRole('admin');

    expect($user->last_login_at)->toBeNull();

    $this->post(route('site.auth.login.post'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect();

    expect($user->fresh()->last_login_at)->not->toBeNull();
});
