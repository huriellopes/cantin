<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Categories;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\Category;
use Livewire\Attributes\Title;

#[Title('Categorias')]
class Index extends ResourceComponent
{
    protected function model(): string
    {
        return Category::class;
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
        return 'Categorias';
    }

    protected function singular(): string
    {
        return 'Categoria';
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
