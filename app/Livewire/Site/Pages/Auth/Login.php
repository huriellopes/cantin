<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages\Auth;

use Livewire\Component;

class Login extends Component
{
    public string $image;

    public function mount(): void
    {
        // asset() não deve ser cacheado (mixed content em HTTPS se gravado como http).
        $this->image = asset('assets/images/new/background-outro.png');
    }

    public function render()
    {
        if (auth()->check()) {
            return redirect()->route('site.home');
        }

        return view('livewire.site.pages.auth.login');
    }
}
