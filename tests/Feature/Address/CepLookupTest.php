<?php

use App\Services\Address\ViaCepService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('services.viacep.endpoint', 'https://viacep.com.br/ws');
    config()->set('services.brasilapi.cep_endpoint', 'https://brasilapi.com.br/api/cep/v1');
});

it('resolves an address from ViaCEP', function () {
    Http::fake([
        'viacep.com.br/*' => Http::response([
            'cep' => '01001-000', 'logradouro' => 'Praça da Sé', 'complemento' => 'lado ímpar',
            'bairro' => 'Sé', 'localidade' => 'São Paulo', 'uf' => 'SP',
        ]),
    ]);

    $dto = app(ViaCepService::class)->getAddressInfoFromZipCode('01001-000');

    expect($dto->address)->toBe('Praça da Sé')
        ->and($dto->city)->toBe('São Paulo')
        ->and($dto->state)->toBe('SP');
});

it('falls back to BrasilAPI when ViaCEP fails', function () {
    Http::fake([
        'viacep.com.br/*' => Http::response(['erro' => true]),
        'brasilapi.com.br/*' => Http::response([
            'cep' => '01001000', 'state' => 'SP', 'city' => 'São Paulo',
            'neighborhood' => 'Sé', 'street' => 'Praça da Sé',
        ]),
    ]);

    $dto = app(ViaCepService::class)->getAddressInfoFromZipCode('01001000');

    expect($dto->address)->toBe('Praça da Sé')
        ->and($dto->neighborhood)->toBe('Sé')
        ->and($dto->state)->toBe('SP');
});

it('falls back to BrasilAPI when ViaCEP is unreachable', function () {
    Http::fake([
        'viacep.com.br/*' => fn () => throw new ConnectionException('timeout'),
        'brasilapi.com.br/*' => Http::response([
            'cep' => '01001000', 'state' => 'SP', 'city' => 'São Paulo', 'neighborhood' => 'Sé', 'street' => 'Praça da Sé',
        ]),
    ]);

    $dto = app(ViaCepService::class)->getAddressInfoFromZipCode('01001000');

    expect($dto->city)->toBe('São Paulo');
});

it('throws when both providers fail', function () {
    Http::fake([
        'viacep.com.br/*' => Http::response(['erro' => true]),
        'brasilapi.com.br/*' => Http::response([], 404),
    ]);

    app(ViaCepService::class)->getAddressInfoFromZipCode('00000000');
})->throws(Exception::class);

it('rejects an invalid zipcode', function () {
    app(ViaCepService::class)->getAddressInfoFromZipCode('123');
})->throws(Exception::class);
