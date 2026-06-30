<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages\Auth;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Login extends Component
{
    public string $image = '';

    public function mount(): void
    {
        // Usuário já autenticado não deve ver o login. O redirect fica no mount
        // (não no render): retornar um Redirector do render() de um componente
        // full-page quebra (500) no Livewire 4.
        if (auth()->check()) {
            $this->redirectRoute('site.home', navigate: true);

            return;
        }

        // asset() não deve ser cacheado (mixed content em HTTPS se gravado como http).
        $this->image = asset('assets/images/new/background-outro.png');
    }

    public function render(): Factory|View
    {
        return view('livewire.site.pages.auth.login');
    }
}
