<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

/**
 * Form object do CRUD de links externos (admin). editingId guarda o registro
 * em edição para o unique de slug ignorar o próprio.
 */
class ExternalLinkForm extends Form
{
    public ?int $editingId = null;

    public string $title = '';

    public string $slug = '';

    public ?int $type_external_link_id = null;

    /** Já inicia com o esquema para orientar o preenchimento; normalizado no save. */
    public string $url = 'https://';

    public string $description = '';

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', Rule::unique('external_links', 'slug')->ignore($this->editingId)],
            'type_external_link_id' => ['required', 'exists:type_external_links,id'],
            'url' => ['required', 'url'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }
}
