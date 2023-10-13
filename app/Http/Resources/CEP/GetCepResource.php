<?php

namespace App\Http\Resources\CEP;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Fluent;

class GetCepResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $getCep = $this->getCEP($this);

        return [
            'zip_code' => $getCep->cep,
            'address' => $getCep->logradouro,
            'complement' => $getCep->complemento ?: null,
            'neighborhood' => $getCep->bairro,
            'locality' => $getCep->localidade,
            'uf' => $getCep->uf
        ];
    }

    /**
     * @param object $data
     * @return object
     */
    private function getCEP (object $data): object
    {
        $getCep = new Fluent($data);
        $resource = new Fluent($getCep->getAttributes());

        return (object) $resource->resource;
    }
}
