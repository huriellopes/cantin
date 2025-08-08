<?php

namespace App\Livewire\Cantin\Pages\Auth;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class AuthFlip extends Component
{
    public $showLogin = true;

    public string $image;

    public function mount(): void
    {
        $this->image = Cache::remember('cantin-login-register', 60 * 60 * 24, function () {
            return asset('/assets/images/new/background-outro.png');
        });
    }

    public function toggleForm()
    {
        $this->showLogin = !$this->showLogin;
    }

    public function render()
    {
        return view('livewire.cantin.pages.auth.auth-flip');
    }
}
