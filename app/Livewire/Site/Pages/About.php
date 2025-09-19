<?php

namespace App\Livewire\Site\Pages;

use App\Actions\Pages\GetPageAction;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class About extends Component
{
    public function render()
    {
        $page = app(GetPageAction::class)->handle('sobre');

        $image = Cache::remember('cantin-about', 60 * 60 * 24, function () {
            return asset('/assets/images/CANTIn.png');
        });

        return view('livewire.site.pages.about', [
            'image' => $image,
            'page' => $page,
        ]);
    }
}
