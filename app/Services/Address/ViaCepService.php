<?php

namespace App\Services\Address;

use App\Contracts\Address\IAddressService;
use App\Http\DTO\Address\AddressResultDTO;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ViaCepService implements IAddressService
{
    public function getAddressInfoFromZipCode(string $zipcode): AddressResultDTO
    {
        return Cache::remember('viacep-'.$zipcode, 60 * 60 * 24, function () use ($zipcode): ?\App\Http\DTO\Address\AddressResultDTO {
            $response = Http::timeout(3000)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->get(config('services.viacep.endpoint').'/'.$zipcode.'/json');

            if ($response->failed()) {
                throw new Exception(__('Error when searching for zip code!'), Response::HTTP_BAD_REQUEST);
            }

            if ($response->json('erro')) {
                throw new Exception(__('Zip code not found!'), Response::HTTP_BAD_REQUEST);
            }

            if ($response->successful()) {
                $data = $response->json();

                return new AddressResultDTO(
                    zipcode: $data['cep'],
                    address: $data['logradouro'],
                    neighborhood: $data['bairro'],
                    complement: $data['complemento'],
                    city: $data['localidade'],
                    state: $data['uf'],
                );
            }

            return null;
        });
    }
}
