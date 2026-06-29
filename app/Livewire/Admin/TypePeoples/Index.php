<?php

namespace App\Livewire\Admin\TypePeoples;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\TypePeople;
use Livewire\Attributes\Title;

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
            'name' => ['label' => 'Nome'],
            'slug' => ['label' => 'Slug', 'unique' => true],
            'description' => ['label' => 'Descrição', 'rules' => ['nullable', 'string', 'max:255']],
        ];
    }

    protected function heading(): string
    {
        return 'Gêneros';
    }

    protected function singular(): string
    {
        return 'Gênero';
    }

    protected function searchable(): array
    {
        return ['name', 'slug'];
    }
}
