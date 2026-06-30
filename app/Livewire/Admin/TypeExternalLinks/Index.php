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
            'name' => ['label' => __('msg_type_external_links.field_name_label')],
            'slug' => ['label' => __('msg_type_external_links.field_slug_label'), 'unique' => true],
        ];
    }

    protected function heading(): string
    {
        return __('msg_type_external_links.heading');
    }

    protected function singular(): string
    {
        return __('msg_type_external_links.singular');
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
