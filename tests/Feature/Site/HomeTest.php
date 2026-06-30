<?php

declare(strict_types=1);

use App\Enum\Status;
use App\Livewire\Site\Pages\Home;
use App\Models\CommonQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('render home page', function () {
    Livewire::test(Home::class)
        ->assertViewIs('livewire.site.pages.home');
});

it('not render questions in home page', function () {
    Livewire::test(Home::class)
        ->assertViewHas('commons', fn ($commons) => $commons->count() === 0);
});

it('render questions in home page', function () {
    collect(range(1, 3))->each(fn (int $i) => CommonQuestion::create([
        'question' => "Pergunta {$i}?",
        'answer' => "Resposta {$i}.",
        'status' => Status::ACTIVE,
    ]));

    Livewire::test(Home::class)
        ->assertViewHas('commons', fn ($commons) => $commons->count() === 3);
});
