<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Profile;

use App\Livewire\Admin\Support\HasAdminActions;
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

    public string $name = '';

    public string $email = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $showDelete = false;

    public string $delete_password = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($data);
        $this->notify(__('admin.profile.updated'));
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update(['password' => Hash::make($this->password)]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->notify(__('admin.profile.password_changed'));
    }

    public function confirmDeleteAccount(): void
    {
        $this->reset('delete_password');
        $this->resetValidation();
        $this->showDelete = true;
    }

    public function deleteAccount()
    {
        $this->validate(
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
