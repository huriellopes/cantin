<?php

use Livewire\Livewire;
use App\Livewire\Cantin\Pages\About;

it('render about page', function () {
    Livewire::test(About::class)
        ->assertViewIs('livewire.cantin.pages.about');
});
