<?php

declare(strict_types=1);

use App\Actions\Address\FillAddressAction;
use App\Models\City;
use App\Models\State;
use App\Services\Address\ViaCepService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    config()->set('services.viacep.endpoint', 'https://viacep.com.br/ws');
    config()->set('services.brasilapi.cep_endpoint', 'https://brasilapi.com.br/api/cep/v1');
});

it('resolves an address from ViaCEP', function (): void {
    Http::fake([
        'viacep.com.br/*' => Http::response([
            'cep' => '01001-000', 'logradouro' => 'Praça da Sé', 'complemento' => 'lado ímpar',
            'bairro' => 'Sé', 'localidade' => 'São Paulo', 'uf' => 'SP',
        ]),
    ]);

    $dto = resolve(ViaCepService::class)->getAddressInfoFromZipCode('01001-000');

    expect($dto->address)->toBe('Praça da Sé')
        ->and($dto->city)->toBe('São Paulo')
        ->and($dto->state)->toBe('SP');
});

it('falls back to BrasilAPI when ViaCEP fails', function (): void {
    Http::fake([
        'viacep.com.br/*' => Http::response(['erro' => true]),
        'brasilapi.com.br/*' => Http::response([
            'cep' => '01001000', 'state' => 'SP', 'city' => 'São Paulo',
            'neighborhood' => 'Sé', 'street' => 'Praça da Sé',
        ]),
    ]);

    $dto = resolve(ViaCepService::class)->getAddressInfoFromZipCode('01001000');

    expect($dto->address)->toBe('Praça da Sé')
        ->and($dto->neighborhood)->toBe('Sé')
        ->and($dto->state)->toBe('SP');
});

it('falls back to BrasilAPI when ViaCEP is unreachable', function (): void {
    Http::fake([
        'viacep.com.br/*' => fn () => throw new ConnectionException('timeout'),
        'brasilapi.com.br/*' => Http::response([
            'cep' => '01001000', 'state' => 'SP', 'city' => 'São Paulo', 'neighborhood' => 'Sé', 'street' => 'Praça da Sé',
        ]),
    ]);

    $dto = resolve(ViaCepService::class)->getAddressInfoFromZipCode('01001000');

    expect($dto->city)->toBe('São Paulo');
});

it('throws when both providers fail', function (): void {
    Http::fake([
        'viacep.com.br/*' => Http::response(['erro' => true]),
        'brasilapi.com.br/*' => Http::response([], 404),
    ]);

    resolve(ViaCepService::class)->getAddressInfoFromZipCode('00000000');
})->throws(Exception::class);

it('rejects an invalid zipcode', function (): void {
    resolve(ViaCepService::class)->getAddressInfoFromZipCode('123');
})->throws(Exception::class);

it('resolves state and city ids case-insensitively (DB stores uppercase)', function (): void {
    // A base guarda nomes em CAIXA ALTA; a API retorna caixa mista.
    Http::fake(['viacep.com.br/*' => Http::response([
        'cep' => '50010-000', 'logradouro' => 'Rua do Sol', 'complemento' => '',
        'bairro' => 'Centro', 'localidade' => 'Recife', 'uf' => 'PE',
    ])]);

    $state = State::query()->forceCreate(['name' => 'Pernambuco', 'abbr' => 'pe', 'slug' => 'pe']);
    $city = City::query()->forceCreate(['name' => 'RECIFE', 'slug' => 'recife', 'state_id' => $state->id]);

    $result = FillAddressAction::exec('50010000');

    expect($result->state)->toBe($state->id)
        ->and($result->city)->toBe($city->id);
});
