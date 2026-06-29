<?php

use App\Livewire\Admin\Terreiros\Index as TerreirosIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;
use Livewire\Livewire;

it('exports users as a csv download', function () {
    userWithRole('user');

    Livewire::actingAs(userWithRole('super-admin'))
        ->test(UsersIndex::class)
        ->call('exportCsv')
        ->assertFileDownloaded();
});

it('exports terreiros as a csv download', function () {
    Livewire::actingAs(userWithRole('admin'))
        ->test(TerreirosIndex::class)
        ->call('exportCsv')
        ->assertFileDownloaded();
});
