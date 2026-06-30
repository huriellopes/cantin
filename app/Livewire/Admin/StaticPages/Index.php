<?php

declare(strict_types=1);

namespace App\Livewire\Admin\StaticPages;

use App\Livewire\Admin\Support\ResourceComponent;
use App\Models\StaticPage;
use Livewire\Attributes\Title;
use Override;

#[Title('Páginas Estáticas')]
class Index extends ResourceComponent
{
    protected function model(): string
    {
        return StaticPage::class;
    }

    protected function fields(): array
    {
        return [
            'name' => ['label' => __('msg_static_pages.label_name')],
            'slug' => ['label' => __('msg_static_pages.label_slug'), 'unique' => true],
            'content' => ['label' => __('msg_static_pages.label_content'), 'type' => 'textarea', 'rules' => ['required', 'string']],
        ];
    }

    protected function heading(): string
    {
        return __('msg_static_pages.heading');
    }

    protected function singular(): string
    {
        return __('msg_static_pages.singular');
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
    protected function onCreate(): array
    {
        return ['user_id' => auth()->id()];
    }
}
