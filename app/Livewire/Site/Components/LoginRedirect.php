<?php

namespace App\Livewire\Site\Components;

use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class LoginRedirect extends Component
{
    public function redirectToLogin(): RedirectResponse
    {
        return redirect()->route('site.auth.login');
    }

    public function render()
    {
        return view('livewire.site.components.login-redirect');
    }
}
