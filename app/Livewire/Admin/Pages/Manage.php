<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages;

use App\Livewire\Admin\Support\ResourceManageComponent;
use App\Models\Page;
use Livewire\Attributes\Title;
use Override;

#[Title('Página')]
class Manage extends ResourceManageComponent
{
    public function mount(?Page $page = null): void
    {
        $this->initRecord($page);
    }

    protected function model(): string
    {
        return Page::class;
    }

    protected function fields(): array
    {
        return [
            'name' => ['label' => __('msg_pages.label_name')],
            'slug' => ['label' => __('msg_pages.label_slug'), 'unique' => true],
            'content' => ['label' => __('msg_pages.label_content'), 'type' => 'richtext', 'rules' => ['required', 'string']],
        ];
    }

    protected function singular(): string
    {
        return __('msg_pages.singular');
    }

    protected function indexRoute(): string
    {
        return 'admin.pages.index';
    }

    #[Override]
    protected function hasStatus(): bool
    {
        return true;
    }
}
