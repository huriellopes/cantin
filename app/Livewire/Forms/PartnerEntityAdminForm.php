<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

/**
 * Form object do CRUD admin de entidades parceiras (dados + endereço + imagem).
 * A imagem é obrigatória na criação e opcional na edição (editingId).
 */
class PartnerEntityAdminForm extends AddressForm
{
    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $activity_carried_out = '';

    public $image;

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'activity_carried_out' => ['required', 'string'],
            'image' => [$this->editingId ? 'nullable' : 'required', 'image', 'max:4096'],
        ], $this->addressRules());
    }
}
