<?php

namespace App\Livewire\Cantin\Pages;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class About extends Component
{
    public string $image;

    public function mount()
    {
        $this->image = Cache::remember('cantin-about', 60 * 60 * 24, function () {
            return asset('/assets/images/CANTIn.png');
        });
    }

    public function render()
    {
        return view('livewire.cantin.pages.about');
    }
}
