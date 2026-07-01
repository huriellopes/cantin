<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Actions\Address\FillAddressAction;
use App\Models\Address;
use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Form;
use Throwable;

/**
 * Base dos Form objects do admin que cadastram um endereço (Terreiros, Pessoas
 * Trans, Entidades). Concentra os campos de endereço, a busca por CEP e a
 * persistência — antes vividos no trait InteractsWithAddress. Cada Form filho
 * adiciona seus próprios campos e mescla as regras via addressRules().
 */
abstract class AddressForm extends Form
{
    public string $zipcode = '';

    public string $address = '';

    public string $complement = '';

    public string $neighborhood = '';

    public ?int $state_id = null;

    public ?int $city_id = null;

    /**
     * Preenche o endereço a partir do CEP. Chamado pelo componente (delegação),
     * já que wire:click aponta para um método do componente.
     */
    public function buscarCep(): void
    {
        try {
            $data = FillAddressAction::exec(preg_replace('/\D/', '', $this->zipcode));
            $this->address = $data->address ?? $this->address;
            $this->neighborhood = $data->neighborhood ?? $this->neighborhood;
            $this->complement = $data->complement ?? $this->complement;
            $this->state_id = $data->state ?? $this->state_id;
            $this->city_id = $data->city ?? $this->city_id;
        } catch (Throwable) {
            $this->addError('zipcode', 'Não foi possível buscar o CEP.');
        }
    }

    public function fillAddressFrom(?Model $address): void
    {
        if (!$address instanceof Address) {
            return;
        }

        $this->zipcode = (string) $address->zipcode;
        $this->address = (string) $address->address;
        $this->complement = (string) $address->complement;
        $this->neighborhood = (string) $address->neighborhood;
        $this->state_id = $address->state_id;
        $this->city_id = $address->city_id;
    }

    public function persistAddress(): Address
    {
        return Address::query()->updateOrCreate(
            // O CEP é gravado só com dígitos; casa a busca com o valor limpo.
            ['zipcode' => preg_replace('/\D/', '', $this->zipcode)],
            [
                'address' => $this->address,
                'complement' => $this->complement,
                'neighborhood' => $this->neighborhood,
                'state_id' => $this->state_id,
                'city_id' => $this->city_id,
            ],
        );
    }

    /** @return Collection<int, string> */
    public function statesOptions(): Collection
    {
        return State::query()->orderBy('name')->pluck('name', 'id');
    }

    /** @return Collection<int, string> */
    public function citiesOptions(): Collection
    {
        return $this->state_id
            ? City::query()->where('state_id', $this->state_id)->orderBy('name')->pluck('name', 'id')
            : collect();
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
}
