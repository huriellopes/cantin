<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

/**
 * Form object do cadastro público de entidades parceiras (contato + endereço
 * + atividade exercida).
 */
class PartnerEntityForm extends AddressContactForm
{
    public string $activity_carried_out = '';

    /**
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return array_merge($this->addressContactRules(), [
            'activity_carried_out' => 'required|string',
        ]);
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return array_merge($this->addressContactMessages(), [
            'activity_carried_out.required' => __('The activity carried out field is required.'),
        ]);
    }
}
