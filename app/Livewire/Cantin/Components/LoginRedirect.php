<?php

namespace App\Livewire\Cantin\Components;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class LoginRedirect extends Component
{
    public function redirectToLogin() : RedirectResponse
    {
        return redirect()->route('filament.auth.login');
    }

    public function render()
    {
        return view('livewire.cantin.components.login-redirect');
    }
}
