<?php

namespace App\Livewire\Site\Pages\Auth;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Login extends Component
{
    public $showLogin = true;

    public string $image;

    public function mount(): void
    {
        $this->image = Cache::remember('cantin-login-register', 60 * 60 * 24, function () {
            return asset('/assets/images/new/background-outro.png');
        });
    }

    public function toggleForm(): void
    {
        $this->showLogin = !$this->showLogin;
    }

    public function render()
    {
        if (auth()->check()) {
            redirect()->route('filament.admin.pages.dashboard');
        }

        return view('livewire.site.pages.auth.login');
    }
}
