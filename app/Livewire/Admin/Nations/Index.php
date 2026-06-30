<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Nations;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\NationsTerreiro;
use Livewire\Attributes\Title;
use Override;

#[Title('Nações')]
class Index extends ResourceComponent
{
    protected function model(): string
    {
        return NationsTerreiro::class;
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
        return 'Nações';
    }

    protected function singular(): string
    {
        return 'Nação';
    }

    #[Override]
    protected function searchable(): array
    {
        return ['name', 'slug'];
    }
}
