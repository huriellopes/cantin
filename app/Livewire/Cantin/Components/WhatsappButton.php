<?php

namespace App\Livewire\Cantin\Components;

use Livewire\Component;

class WhatsappButton extends Component
{
    public $phoneNumber = '5561999776608';

    public function openWhatsapp()
    {
        $url = "https://wa.me/{$this->phoneNumber}";
        return redirect($url);
    }

    public function render()
    {
        return view('livewire.cantin.components.whatsapp-button');
    }
}
