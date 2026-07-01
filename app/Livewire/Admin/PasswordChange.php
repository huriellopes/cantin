<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Livewire\Forms\PasswordChangeForm;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Tela de troca obrigatória de senha. Exibida quando o usuário ainda está com
 * a senha padrão (password_change_required). Após trocar, libera o painel.
 */
#[Layout('components.layouts.guest')]
#[Title('Trocar senha')]
class PasswordChange extends Component
{
    public PasswordChangeForm $form;

    public function save(): void
    {
        $this->form->validate();

        $user = auth()->user();
        $user->update([
            'password' => $this->form->password,
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
