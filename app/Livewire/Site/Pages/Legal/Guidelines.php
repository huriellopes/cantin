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
#[Title('Diretrizes')]
class Guidelines extends Component
{
    public function render(): Factory|View
    {
        // Conteúdo editável pelo admin (menu Páginas, slug "diretrizes"). A Page
        // é pt-BR: só a usamos no locale pt_BR; em outros idiomas cai no conteúdo
        // estático traduzido (i18n) da view.
        $page = app()->getLocale() === 'pt_BR'
            ? resolve(GetPageAction::class)->handle('diretrizes')
            : null;

        return view('livewire.site.pages.legal.guidelines', [
            'page' => $page,
        ]);
    }
}
