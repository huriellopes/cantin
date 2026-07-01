<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

/**
 * Form object do CRUD admin de pessoas trans (dados pessoais + endereço).
 */
class TransPersonForm extends AddressForm
{
    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
        ], $this->addressRules());
    }
}
