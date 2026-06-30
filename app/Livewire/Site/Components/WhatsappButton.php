<?php

declare(strict_types=1);

namespace App\Livewire\Site\Components;

use Livewire\Component;

class WhatsappButton extends Component
{
    public $phoneNumber = '+5561999776608';

    public function openWhatsapp()
    {
        $url = "https://wa.me/{$this->phoneNumber}";

        return redirect($url);
    }

    public function render()
    {
        return view('livewire.site.components.whatsapp-button');
    }
}
