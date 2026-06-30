<?php

declare(strict_types=1);

use App\Models\City;
use App\Models\State;
use Illuminate\Support\Facades\Http;

it('syncs states and cities from the IBGE API', function () {
    Http::fake([
        '*/estados' => Http::response([
            ['id' => 35, 'sigla' => 'SP', 'nome' => 'São Paulo'],
            ['id' => 26, 'sigla' => 'PE', 'nome' => 'Pernambuco'],
        ]),
        '*/municipios' => Http::response([
            ['id' => 3550308, 'nome' => 'São Paulo', 'microrregiao' => ['mesorregiao' => ['UF' => ['sigla' => 'SP']]]],
            ['id' => 2611606, 'nome' => 'Recife', 'microrregiao' => ['mesorregiao' => ['UF' => ['sigla' => 'PE']]]],
        ]),
    ]);

    $this->artisan('localidades:sync')->assertSuccessful();

    expect(State::query()->count())->toBe(2)
        ->and(City::query()->count())->toBe(2);

    $sp = State::query()->where('abbr', 'SP')->first();
    $pe = State::query()->where('abbr', 'PE')->first();

    expect($sp->ibge_code)->toBe(35)
        ->and(City::query()->where('name', 'São Paulo')->where('state_id', $sp->id)->exists())->toBeTrue()
        ->and(City::query()->where('name', 'Recife')->where('state_id', $pe->id)->exists())->toBeTrue();
});

it('reseeds (replaces) existing localidades on each sync', function () {
    State::query()->forceCreate(['name' => 'Antigo', 'abbr' => 'XX', 'slug' => 'antigo']);

    Http::fake([
        '*/estados' => Http::response([['id' => 35, 'sigla' => 'SP', 'nome' => 'São Paulo']]),
        '*/municipios' => Http::response([
            ['id' => 3550308, 'nome' => 'São Paulo', 'microrregiao' => ['mesorregiao' => ['UF' => ['sigla' => 'SP']]]],
        ]),
    ]);

    $this->artisan('localidades:sync')->assertSuccessful();

    expect(State::query()->where('abbr', 'XX')->exists())->toBeFalse()
        ->and(State::query()->count())->toBe(1);
});
