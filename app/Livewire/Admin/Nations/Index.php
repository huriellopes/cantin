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
            'name' => ['label' => __('msg_nations.field_name_label')],
            'slug' => ['label' => __('msg_nations.field_slug_label'), 'unique' => true],
        ];
    }

    protected function heading(): string
    {
        return __('msg_nations.heading');
    }

    protected function singular(): string
    {
        return __('msg_nations.singular');
    }

    #[Override]
    protected function searchable(): array
    {
        return ['name', 'slug'];
    }
}
