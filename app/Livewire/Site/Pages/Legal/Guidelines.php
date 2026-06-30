<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages\Legal;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Diretrizes')]
class Guidelines extends Component
{
    public function render()
    {
        return view('livewire.site.pages.legal.guidelines');
    }
}
