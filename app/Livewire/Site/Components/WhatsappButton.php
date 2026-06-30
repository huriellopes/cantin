<?php

declare(strict_types=1);

namespace App\Livewire\Site\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Livewire\Component;

class WhatsappButton extends Component
{
    public $phoneNumber = '+5561999776608';

    public function openWhatsapp(): Redirector|RedirectResponse
    {
        $url = "https://wa.me/{$this->phoneNumber}";

        return redirect($url);
    }

    public function render(): Factory|View
    {
        return view('livewire.site.components.whatsapp-button');
    }
}
