<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages;

use App\Actions\Pages\GetPageAction;
use Livewire\Component;

class About extends Component
{
    public function render()
    {
        $page = app(GetPageAction::class)->handle('sobre');

        $image = asset('assets/images/CANTIn.png');

        return view('livewire.site.pages.about', [
            'image' => $image,
            'page' => $page,
        ]);
    }
}
