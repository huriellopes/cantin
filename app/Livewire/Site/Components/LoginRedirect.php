<?php

declare(strict_types=1);

namespace App\Livewire\Site\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class LoginRedirect extends Component
{
    public function redirectToLogin(): RedirectResponse
    {
        return to_route('site.auth.login');
    }

    public function render(): Factory|View
    {
        return view('livewire.site.components.login-redirect');
    }
}
