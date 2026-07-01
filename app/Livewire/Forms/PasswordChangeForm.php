<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Form;

/**
 * Form object da troca obrigatória de senha. Concentra os campos, as regras e
 * as mensagens de validação, mantendo o componente enxuto (padrão Livewire Form).
 */
class PasswordChangeForm extends Form
{
    public string $password = '';

    public string $password_confirmation = '';

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                // Não pode manter a senha padrão.
                Rule::notIn([User::DEFAULT_PASSWORD]),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'password.not_in' => __('msg_password_change.not_default'),
        ];
    }
}
