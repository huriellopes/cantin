<?php

declare(strict_types=1);

use App\Livewire\Site\Pages\PartnersEntities;
use App\Livewire\Site\Pages\Terreiros\Create;
use App\Livewire\Site\Pages\Transpeople;
use App\Models\City;
use App\Models\State;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

function fakeSpCep(): array
{
    Http::fake(['viacep.com.br/*' => Http::response([
        'cep' => '01001-000', 'logradouro' => 'Praça da Sé', 'complemento' => 'lado ímpar',
        'bairro' => 'Sé', 'localidade' => 'São Paulo', 'uf' => 'SP',
    ])]);

    $state = State::query()->forceCreate(['name' => 'São Paulo', 'abbr' => 'SP', 'slug' => 'sao-paulo']);
    $city = City::query()->forceCreate(['name' => 'São Paulo', 'slug' => 'sao-paulo', 'state_id' => $state->id]);

    return [$state, $city];
}

it('fills the address from a CEP on the public terreiro form', function (): void {
    Http::fake(['viacep.com.br/*' => Http::response([
        'cep' => '01001-000', 'logradouro' => 'Praça da Sé', 'complemento' => 'lado ímpar',
        'bairro' => 'Sé', 'localidade' => 'São Paulo', 'uf' => 'SP',
    ])]);

    $state = State::query()->forceCreate(['name' => 'São Paulo', 'abbr' => 'SP', 'slug' => 'sao-paulo']);
    $city = City::query()->forceCreate(['name' => 'São Paulo', 'slug' => 'sao-paulo', 'state_id' => $state->id]);

    Livewire::test(Create::class)
        ->set('form.zipcode', '01001-000')
        ->call('searchZipCode')
        ->assertHasNoErrors()
        ->assertSet('form.street', 'Praça da Sé')
        ->assertSet('form.neighborhood', 'Sé')
        ->assertSet('form.state_id', $state->id)
        ->assertSet('form.city_id', $city->id);
});

it('shows an error for an invalid zipcode on the public form', function (): void {
    Livewire::test(Create::class)
        ->set('form.zipcode', '123')
        ->call('searchZipCode')
        ->assertHasErrors('form.zipcode');
});

it('fills the address from a CEP on the trans people form', function (): void {
    [$state, $city] = fakeSpCep();

    Livewire::test(Transpeople::class)
        ->set('form.zipcode', '01001-000')
        ->call('searchZipCode')
        ->assertHasNoErrors()
        ->assertSet('form.street', 'Praça da Sé')
        ->assertSet('form.state_id', $state->id)
        ->assertSet('form.city_id', $city->id);
});

it('fills the address from a CEP on the partner entities form', function (): void {
    [$state, $city] = fakeSpCep();

    Livewire::test(PartnersEntities::class)
        ->set('form.zipcode', '01001-000')
        ->call('searchZipCode')
        ->assertHasNoErrors()
        ->assertSet('form.street', 'Praça da Sé')
        ->assertSet('form.state_id', $state->id)
        ->assertSet('form.city_id', $city->id);
});
