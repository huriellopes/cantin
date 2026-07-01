<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

/**
 * Form object do cadastro público de pessoas trans (contato + endereço).
 */
class TranspeopleForm extends AddressContactForm
{
    /**
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return $this->addressContactRules();
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return $this->addressContactMessages();
    }
}
