<?php

declare(strict_types=1);

namespace App\Livewire\Site\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class WhatsappButton extends Component
{
    public string $phoneNumber = '+5561999776608';

    public function render(): Factory|View
    {
        return view('livewire.site.components.whatsapp-button', [
            // wa.me usa apenas dígitos; abre em nova aba pelo link (target=_blank).
            'whatsappUrl' => 'https://wa.me/' . preg_replace('/\D/', '', $this->phoneNumber),
        ]);
    }
}
