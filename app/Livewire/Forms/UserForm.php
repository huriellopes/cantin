<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

/**
 * Form object do CRUD de usuários (admin). editingId guarda o registro em
 * edição para o unique de e-mail ignorar o próprio.
 */
class UserForm extends Form
{
    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public ?int $role_id = null;

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingId)],
            'role_id' => ['required', Rule::exists('roles', 'id')],
        ];
    }
}
