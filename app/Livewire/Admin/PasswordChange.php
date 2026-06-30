<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Tela de troca obrigatória de senha. Exibida quando o usuário ainda está com
 * a senha padrão (password_change_required). Após trocar, libera o painel.
 */
#[Layout('components.layouts.admin')]
#[Title('Trocar senha')]
class PasswordChange extends Component
{
    public string $password = '';

    public string $password_confirmation = '';

    public function save(): void
    {
        $this->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                // Não pode manter a senha padrão.
                Rule::notIn([User::DEFAULT_PASSWORD]),
            ],
        ], [
            'password.not_in' => __('msg_password_change.not_default'),
        ]);

        $user = auth()->user();
        $user->update([
            'password' => $this->password,
            'password_change_required' => false,
        ]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => __('msg_password_change.success'),
        ]);

        $this->redirectRoute('admin.dashboard', navigate: true);
    }

    public function render(): Factory|View
    {
        return view('livewire.admin.password-change');
    }
}
