<?php

declare(strict_types=1);

namespace App\Livewire\Admin\TypePeoples;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\TypePeople;
use Livewire\Attributes\Title;
use Override;

#[Title('Gêneros')]
class Index extends ResourceComponent
{
    protected function model(): string
    {
        return TypePeople::class;
    }

    protected function fields(): array
    {
        return [
            'name' => ['label' => __('msg_type_peoples.label_name')],
            'slug' => ['label' => __('msg_type_peoples.label_slug'), 'unique' => true],
            'description' => ['label' => __('msg_type_peoples.label_description'), 'rules' => ['nullable', 'string', 'max:255']],
        ];
    }

    protected function heading(): string
    {
        return __('msg_type_peoples.heading');
    }

    protected function singular(): string
    {
        return __('msg_type_peoples.singular');
    }

    #[Override]
    protected function searchable(): array
    {
        return ['name', 'slug'];
    }
}
