<?php

declare(strict_types=1);

namespace App\Livewire\Admin\TypeTerreiros;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\TypeTerreiro;
use Livewire\Attributes\Title;
use Override;

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
            'name' => ['label' => __('msg_type_terreiros.field_name')],
            'slug' => ['label' => __('msg_type_terreiros.field_slug'), 'unique' => true],
        ];
    }

    protected function heading(): string
    {
        return __('msg_type_terreiros.heading');
    }

    protected function singular(): string
    {
        return __('msg_type_terreiros.singular');
    }

    #[Override]
    protected function searchable(): array
    {
        return ['name', 'slug'];
    }
}
