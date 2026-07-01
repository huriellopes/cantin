<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Profile;

use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Forms\ProfileForm;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Meu perfil')]
class Index extends Component
{
    use HasAdminActions;

    public ProfileForm $form;

    public bool $showDelete = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->form->name = $user->name;
        $this->form->email = $user->email;
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $data = $this->form->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($data);
        $this->notify(__('admin.profile.updated'));
    }

    public function updatePassword(): void
    {
        $this->form->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update(['password' => Hash::make($this->form->password)]);

        $this->form->reset(['current_password', 'password', 'password_confirmation']);
        $this->notify(__('admin.profile.password_changed'));
    }

    public function confirmDeleteAccount(): void
    {
        $this->form->reset('delete_password');
        $this->resetValidation();
        $this->showDelete = true;
    }

    public function deleteAccount()
    {
        $this->form->validate(
            ['delete_password' => ['required', 'current_password']],
            ['delete_password.current_password' => __('admin.profile.wrong_password')],
        );

        $user = Auth::user();
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        User::query()->whereKey($user->id)->delete();

        return to_route('site.home');
    }

    public function render(): Factory|View
    {
        return view('livewire.admin.profile.index');
    }
}
