<?php

use Livewire\Livewire;
use App\Livewire\Cantin\Pages\Home;
use App\Models\CommonQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('render home page', function () {
    Livewire::test(Home::class)
        ->assertViewIs('livewire.cantin.pages.home');
});

it('not render questions in home page', function () {
    $commons = CommonQuestion::query()
        ->select('id', 'answer','question')
        ->active()
        ->get();

    Livewire::test(Home::class)
        ->assertViewHas('commons', function () use ($commons) {
            return count($commons) === 0;
        });
});

it ('render questions in home page', function () {
    $commons = CommonQuestion::query()
        ->select('id', 'answer','question')
        ->active()
        ->get();
    dd($commons);
    Livewire::test(Home::class)
        ->assertViewHas('commons', function () use ($commons) {
            return count($commons) > 0;
        });
});
