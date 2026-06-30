<?php

declare(strict_types=1);

namespace App\Livewire\Admin\TypeExternalLinks;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\TypeExternalLink;
use Livewire\Attributes\Title;
use Override;

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

    #[Override]
    protected function searchable(): array
    {
        return ['name', 'slug'];
    }

    #[Override]
    protected function hasStatus(): bool
    {
        return true;
    }
}
