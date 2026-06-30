<?php

declare(strict_types=1);

use App\Livewire\Site\Pages\Home;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('render home page', function (): void {
    Livewire::test(Home::class)
        ->assertViewIs('livewire.site.pages.home');
});

it('renders the FAQ from the translation files', function (): void {
    Livewire::test(Home::class)
        ->assertViewHas('commons', fn ($commons): bool => is_array($commons) && count($commons) === count(__('faq.items')))
        ->assertSee('O que é o CANTIn?');
});

it('renders the FAQ in English when the locale is English', function (): void {
    app()->setLocale('en');

    Livewire::test(Home::class)
        ->assertSee('What is CANTIn?')
        ->assertDontSee('O que é o CANTIn?');
});
