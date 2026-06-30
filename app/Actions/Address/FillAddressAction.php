<?php

declare(strict_types=1);

namespace App\Actions\Address;

use App\Models\City;
use App\Models\State;
use App\Services\Address\ViaCepService;
use App\Traits\Utils;
use Exception;
use Geocoder\Laravel\Facades\Geocoder;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class FillAddressAction
{
    use Utils;

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

            // Comparação case-insensitive: o ViaCEP/BrasilAPI retorna nomes em
            // caixa mista, mas a base os armazena em CAIXA ALTA (ex.: "SÃO PAULO").
            $state_id = State::query()
                ->whereRaw('UPPER(abbr) = UPPER(?)', [$address->state])
                ->pluck('id')
                ->first();

            $city_id = City::query()
                ->whereRaw('UPPER(name) = UPPER(?)', [$address->city])
                ->where('state_id', $state_id)
                ->pluck('id')
                ->first();

            // Geocoding é best-effort: se o provedor (Google Maps) falhar ou não
            // estiver configurado, o endereço ainda é preenchido (lat/long ficam nulos).
            $latitude = null;
            $longitude = null;

            try {
                $street = $address->address . ',' . str($address->zipcode)->replace('-', '') . ',' . $address->neighborhood . ',' . $address->state . ', Brasil';
                $result = Geocoder::geocode($street)->get();

                if ($result->isNotEmpty()) {
                    $coordinates = $result->first()->getCoordinates();
                    $latitude = $coordinates->getLatitude();
                    $longitude = $coordinates->getLongitude();
                }
            } catch (Exception|Throwable $e) {
                Log::warning('Geocoding indisponível; lat/long não preenchidos.', ['error' => $e->getMessage()]);
            }

            $data = [
                'address' => $address->address,
                'neighborhood' => $address->neighborhood,
                'complement' => $address->complement,
                'state' => $state_id,
                'city' => $city_id,
                'latitude' => $latitude ?? null,
                'longitude' => $longitude ?? null,
            ];

            return (object) $data;
        } catch (Exception|Throwable $e) {
            self::webhook('error', $e, 'Cep not found');

            Log::error($e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            toastr()
                ->timeOut(2000)
                ->error(__('Error when searching for zip code!'));

            throw new Exception(__('Error when searching for zip code!'), Response::HTTP_BAD_REQUEST, $e);
        }
    }
}
