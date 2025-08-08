<?php

namespace App\Actions\Address;

use App\Models\City;
use App\Models\State;
use App\Services\Address\ViaCepService;
use App\Traits\Utils;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Throwable;

final class FillAddressAction
{
    use Utils;

    /**
     * @param string $zipcode
     * @return object
     * @throws Exception
     */
    public static function exec(string $zipcode) : object
    {
        try {
            if (empty($zipcode)) {
                throw new Exception(__('Invalid zipcode!'), Response::HTTP_BAD_REQUEST);
            }

            $address = resolve(ViaCepService::class)
                ->getAddressInfoFromZipCode($zipcode);

            $state_id = State::query()
                ->where('abbr', $address->state)
                ->pluck('id')
                ->first();

            $city_id = City::query()
                ->where('name', $address->city)
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
            self::webhook('error', $e, 'Cep not found', null);

            Log::error($e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            toastr()
                ->timeOut(2000)
                ->error(__('Error when searching for zip code!'));

            throw new Exception(__('Error when searching for zip code!'), Response::HTTP_BAD_REQUEST);
        }
    }
}
