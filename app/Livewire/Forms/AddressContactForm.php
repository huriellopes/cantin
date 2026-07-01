<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Form;

/**
 * Base dos formulários públicos que usam o partial `creates/address-phone`
 * (dados de contato + endereço). Concentra os campos, regras e mensagens
 * comuns; cada Form filho adiciona apenas o que lhe é específico.
 */
abstract class AddressContactForm extends Form
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $zipcode = '';

    public string $street = '';

    public string $complement = '';

    public string $neighborhood = '';

    public ?int $state_id = null;

    public ?int $city_id = null;

    /**
     * Regras compartilhadas de contato + endereço.
     *
     * @return array<string, string>
     */
    protected function addressContactRules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|string',
            'phone' => 'required|string',
            'zipcode' => 'required|string',
            'street' => 'required|string',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
        ];
    }

    /**
     * Mensagens compartilhadas de contato + endereço.
     *
     * @return array<string, string>
     */
    protected function addressContactMessages(): array
    {
        return [
            'name.required' => __('The name field is required.'),
            'name.string' => __('The name field only allows characters.'),
            'email.required' => __('The email field is required.'),
            'email.string' => __('The email field only allows characters.'),
            'email.email' => __('The email field is invalid.'),
            'phone.required' => __('The phone field is required.'),
            'phone.string' => __('The phone field only allows characters.'),
            'zipcode.required' => __('The zipcode field is required.'),
            'zipcode.string' => __('The zipcode field only allows characters.'),
            'street.required' => __('The address field is required.'),
            'street.string' => __('The address field only allows characters.'),
            'complement.string' => __('The complement field only allows characters.'),
            'neighborhood.required' => __('The neighborhood field is required.'),
            'neighborhood.string' => __('The neighborhood field only allows characters.'),
            'state_id.required' => __('The state field is required.'),
            'state_id.integer' => __('The state field is only allowed numeric characters.'),
            'city_id.required' => __('The city field is required.'),
            'city_id.integer' => __('The city field is only allowed numeric characters.'),
        ];
    }
}
