<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Categories;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\Category;
use Livewire\Attributes\Title;
use Override;

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
            'name' => ['label' => __('msg_categories.field_name')],
            'slug' => ['label' => __('msg_categories.field_slug'), 'unique' => true],
        ];
    }

    protected function heading(): string
    {
        return __('msg_categories.heading');
    }

    protected function singular(): string
    {
        return __('msg_categories.singular');
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
