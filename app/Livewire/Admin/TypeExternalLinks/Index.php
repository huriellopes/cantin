<?php

namespace App\Livewire\Admin\TypeExternalLinks;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\TypeExternalLink;
use Livewire\Attributes\Title;

#[Title('Tipos de Link')]
class Index extends ResourceComponent
{
    protected function model(): string
    {
        return TypeExternalLink::class;
    }

    protected function fields(): array
    {
        return [
            'name' => ['label' => 'Nome'],
            'slug' => ['label' => 'Slug', 'unique' => true],
        ];
    }

    protected function heading(): string
    {
        return 'Tipos de Link';
    }

    protected function singular(): string
    {
        return 'Tipo de Link';
    }

    protected function searchable(): array
    {
        return ['name', 'slug'];
    }

    protected function hasStatus(): bool
    {
        return true;
    }
}
