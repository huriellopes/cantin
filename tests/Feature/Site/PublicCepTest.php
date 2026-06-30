<?php

declare(strict_types=1);

use App\Livewire\Site\Pages\Terreiros\Create;
use App\Models\City;
use App\Models\State;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

it('fills the address from a CEP on the public terreiro form', function () {
    Http::fake(['viacep.com.br/*' => Http::response([
        'cep' => '01001-000', 'logradouro' => 'Praça da Sé', 'complemento' => 'lado ímpar',
        'bairro' => 'Sé', 'localidade' => 'São Paulo', 'uf' => 'SP',
    ])]);

    $state = State::query()->forceCreate(['name' => 'São Paulo', 'abbr' => 'SP', 'slug' => 'sao-paulo']);
    $city = City::query()->forceCreate(['name' => 'São Paulo', 'slug' => 'sao-paulo', 'state_id' => $state->id]);

    Livewire::test(Create::class)
        ->set('zipcode', '01001-000')
        ->call('searchZipCode')
        ->assertHasNoErrors()
        ->assertSet('street', 'Praça da Sé')
        ->assertSet('neighborhood', 'Sé')
        ->assertSet('state_id', $state->id)
        ->assertSet('city_id', $city->id);
});

it('shows an error for an invalid zipcode on the public form', function () {
    Livewire::test(Create::class)
        ->set('zipcode', '123')
        ->call('searchZipCode')
        ->assertHasErrors('zipcode');
});
