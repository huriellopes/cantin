<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\Page;
use Livewire\Attributes\Title;
use Override;

#[Title('Páginas')]
class Index extends ResourceComponent
{
    protected function model(): string
    {
        return Page::class;
    }

    protected function fields(): array
    {
        return [
            'name' => ['label' => __('msg_pages.label_name')],
            'slug' => ['label' => __('msg_pages.label_slug'), 'unique' => true],
            'content' => ['label' => __('msg_pages.label_content'), 'type' => 'textarea', 'rules' => ['required', 'string']],
        ];
    }

    protected function heading(): string
    {
        return __('msg_pages.heading');
    }

    protected function singular(): string
    {
        return __('msg_pages.singular');
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

    #[Override]
    protected function usesPageEditor(): bool
    {
        return true;
    }

    #[Override]
    protected function createRoute(): ?string
    {
        return 'admin.pages.create';
    }

    #[Override]
    protected function editRoute(): ?string
    {
        return 'admin.pages.edit';
    }
}
