<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Support;

use App\Actions\Address\FillAddressAction;
use App\Models\Address;
use App\Models\City;
use App\Models\State;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Lógica de endereço (busca por CEP + persistência) compartilhada pelos
 * componentes que cadastram um endereço (Terreiros, Pessoas Trans, Entidades).
 */
trait InteractsWithAddress
{
    public string $zipcode = '';

    public string $address = '';

    public string $complement = '';

    public string $neighborhood = '';

    public ?int $state_id = null;

    public ?int $city_id = null;

    public ?string $latitude = null;

    public ?string $longitude = null;

    public function buscarCep(): void
    {
        try {
            $data = FillAddressAction::exec(preg_replace('/\D/', '', $this->zipcode));
            $this->address = $data->address ?? $this->address;
            $this->neighborhood = $data->neighborhood ?? $this->neighborhood;
            $this->complement = $data->complement ?? $this->complement;
            $this->state_id = $data->state ?? $this->state_id;
            $this->city_id = $data->city ?? $this->city_id;
            $this->latitude = $data->latitude ?? null;
            $this->longitude = $data->longitude ?? null;
        } catch (Throwable) {
            $this->addError('zipcode', 'Não foi possível buscar o CEP.');
        }
    }

    /** @return array<string, array<int, string>> */
    protected function addressRules(): array
    {
        return [
            'zipcode' => ['required', 'string'],
            'address' => ['required', 'string'],
            'neighborhood' => ['required', 'string'],
            'state_id' => ['required', 'exists:states,id'],
            'city_id' => ['required', 'exists:cities,id'],
        ];
    }

    protected function fillAddressFrom(?Address $address): void
    {
        if (!$address instanceof Address) {
            return;
        }

        $this->fill($address->only([
            'zipcode', 'address', 'complement', 'neighborhood', 'state_id', 'city_id', 'latitude', 'longitude',
        ]));
    }

    protected function persistAddress(): Address
    {
        return Address::query()->updateOrCreate(
            ['zipcode' => $this->zipcode],
            [
                'address' => $this->address,
                'complement' => $this->complement,
                'neighborhood' => $this->neighborhood,
                'state_id' => $this->state_id,
                'city_id' => $this->city_id,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
        );
    }

    protected function resetAddress(): void
    {
        $this->reset(['zipcode', 'address', 'complement', 'neighborhood', 'state_id', 'city_id', 'latitude', 'longitude']);
    }

    /** @return Collection<int, string> */
    protected function statesOptions(): Collection
    {
        return State::query()->orderBy('name')->pluck('name', 'id');
    }

    /** @return Collection<int, string> */
    protected function citiesOptions(): Collection
    {
        return $this->state_id
            ? City::query()->where('state_id', $this->state_id)->orderBy('name')->pluck('name', 'id')
            : collect();
    }
}
