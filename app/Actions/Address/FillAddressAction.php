<?php

declare(strict_types=1);

namespace App\Actions\Address;

use App\Models\City;
use App\Models\State;
use App\Services\Address\ViaCepService;
use Exception;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class FillAddressAction
{
    /**
     * @throws Exception
     */
    public static function exec(string $zipcode): stdClass
    {
        try {
            if ($zipcode === '' || $zipcode === '0') {
                throw new RuntimeException(__('Invalid zipcode!'), Response::HTTP_BAD_REQUEST);
            }

            $address = resolve(ViaCepService::class)
                ->getAddressInfoFromZipCode($zipcode);

            // Comparação case-insensitive: a API retorna caixa mista, mas a base
            // armazena os nomes em CAIXA ALTA (ex.: "SÃO PAULO").
            $state_id = State::query()
                ->whereRaw('UPPER(abbr) = UPPER(?)', [$address->state])
                ->pluck('id')
                ->first();

            $city_id = City::query()
                ->whereRaw('UPPER(name) = UPPER(?)', [$address->city])
                ->where('state_id', $state_id)
                ->pluck('id')
                ->first();

            return (object) [
                'address' => $address->address,
                'neighborhood' => $address->neighborhood,
                'complement' => $address->complement,
                'state' => $state_id,
                'city' => $city_id,
            ];
        } catch (Exception|Throwable $e) {
            Log::error('Erro ao buscar CEP: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            throw new Exception(__('Error when searching for zip code!'), Response::HTTP_BAD_REQUEST, $e);
        }
    }
}
