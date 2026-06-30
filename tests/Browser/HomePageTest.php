<?php

declare(strict_types=1);

use Laravel\Dusk\Browser;

test('the public home page loads and shows the FAQ', function (): void {
    $this->browse(function (Browser $browser): void {
        $browser->visit('/')
            ->assertPresent('nav')
            ->assertSee('O que é o CANTIn?');
    });
});

test('an anonymous visitor can reach the login page', function (): void {
    $this->browse(function (Browser $browser): void {
        $browser->visit('/login')
            ->assertPathIs('/login')
            ->assertPresent('input[type=email]')
            ->assertPresent('input[type=password]');
    });
});
