<?php

declare(strict_types=1);

use App\Livewire\Site\Pages\About;
use Livewire\Livewire;

it('render about page', function () {
    Livewire::test(About::class)
        ->assertViewIs('livewire.site.pages.about');
});
