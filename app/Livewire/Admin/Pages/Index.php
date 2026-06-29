<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\Page;
use Livewire\Attributes\Title;

#[Title('Páginas')]
class Index extends ResourceComponent
{
    protected function model(): string
    {
        return Page::class;
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
        return 'Páginas';
    }

    protected function singular(): string
    {
        return 'Página';
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
}
