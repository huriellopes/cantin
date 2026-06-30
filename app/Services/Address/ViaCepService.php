<?php

declare(strict_types=1);

namespace App\Services\Address;

use App\Contracts\Address\IAddressService;
use App\Http\DTO\Address\AddressResultDTO;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ViaCepService implements IAddressService
{
    /**
     * Busca o endereço pelo CEP usando o ViaCEP e, em caso de falha,
     * recorre ao BrasilAPI como fallback.
     */
    public function getAddressInfoFromZipCode(string $zipcode): AddressResultDTO
    {
        $zipcode = preg_replace('/\D/', '', $zipcode);

        if (mb_strlen((string) $zipcode) !== 8) {
            throw new Exception(__('Invalid zipcode!'), Response::HTTP_BAD_REQUEST);
        }

        return Cache::remember('cep-' . $zipcode, 60 * 60 * 24, fn (): AddressResultDTO => $this->fromViaCep($zipcode)
            ?? $this->fromBrasilApi($zipcode)
            ?? throw new Exception(__('Zip code not found!'), Response::HTTP_BAD_REQUEST));
    }

    private function fromViaCep(string $zipcode): ?AddressResultDTO
    {
        try {
            $endpoint = mb_rtrim((string) config('services.viacep.endpoint'), '/');
            $response = Http::timeout(5)->acceptJson()->get("{$endpoint}/{$zipcode}/json");

            if ($response->failed() || $response->json('erro')) {
                return null;
            }

            $data = $response->json();

            return new AddressResultDTO(
                zipcode: $data['cep'] ?? $zipcode,
                address: $data['logradouro'] ?? '',
                neighborhood: $data['bairro'] ?? '',
                complement: $data['complemento'] ?? '',
                city: $data['localidade'] ?? '',
                state: $data['uf'] ?? '',
            );
        } catch (Throwable $e) {
            Log::warning('ViaCEP indisponível, tentando fallback (BrasilAPI).', ['cep' => $zipcode, 'error' => $e->getMessage()]);

            return null;
        }
    }

    private function fromBrasilApi(string $zipcode): ?AddressResultDTO
    {
        try {
            $endpoint = mb_rtrim((string) config('services.brasilapi.cep_endpoint'), '/');
            $response = Http::timeout(5)->acceptJson()->get("{$endpoint}/{$zipcode}");

            if ($response->failed()) {
                return null;
            }

            $data = $response->json();

            return new AddressResultDTO(
                zipcode: $data['cep'] ?? $zipcode,
                address: $data['street'] ?? '',
                neighborhood: $data['neighborhood'] ?? '',
                complement: '',
                city: $data['city'] ?? '',
                state: $data['state'] ?? '',
            );
        } catch (Throwable $e) {
            Log::warning('BrasilAPI indisponível.', ['cep' => $zipcode, 'error' => $e->getMessage()]);

            return null;
        }
    }
}
