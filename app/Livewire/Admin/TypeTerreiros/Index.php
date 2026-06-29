<?php

declare(strict_types=1);

namespace App\Livewire\Admin\TypeTerreiros;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\TypeTerreiro;
use Livewire\Attributes\Title;

#[Title('Tipos de Terreiro')]
class Index extends ResourceComponent
{
    protected function model(): string
    {
        return TypeTerreiro::class;
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
        return 'Tipos de Terreiro';
    }

    protected function singular(): string
    {
        return 'Tipo de Terreiro';
    }

    #[\Override]
    protected function searchable(): array
    {
        return ['name', 'slug'];
    }
}
