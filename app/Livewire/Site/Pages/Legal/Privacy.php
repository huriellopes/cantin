<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages\Legal;

use App\Actions\Pages\GetPageAction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Política de Privacidade')]
class Privacy extends Component
{
    public function render(): Factory|View
    {
        // Conteúdo editável pelo admin (menu Páginas, slug "privacidade").
        // Se não houver Página cadastrada, cai no conteúdo estático da view.
        return view('livewire.site.pages.legal.privacy', [
            'page' => resolve(GetPageAction::class)->handle('privacidade'),
        ]);
    }
}
