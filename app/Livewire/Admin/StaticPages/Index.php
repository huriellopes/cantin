<?php

namespace App\Livewire\Admin\StaticPages;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\StaticPage;
use Livewire\Attributes\Title;

#[Title('Páginas Estáticas')]
class Index extends ResourceComponent
{
    protected function model(): string
    {
        return StaticPage::class;
    }

    protected function fields(): array
    {
        return [
            'name' => ['label' => 'Nome'],
            'slug' => ['label' => 'Slug', 'unique' => true],
            'content' => ['label' => 'Conteúdo', 'type' => 'textarea', 'rules' => ['required', 'string']],
        ];
    }

    protected function heading(): string
    {
        return 'Páginas Estáticas';
    }

    protected function singular(): string
    {
        return 'Página Estática';
    }

    #[\Override]
    protected function searchable(): array
    {
        return ['name', 'slug'];
    }

    #[\Override]
    protected function hasStatus(): bool
    {
        return true;
    }

    #[\Override]
    protected function onCreate(): array
    {
        return ['user_id' => auth()->id()];
    }
}
