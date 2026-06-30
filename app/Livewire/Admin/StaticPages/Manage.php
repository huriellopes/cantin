<?php

declare(strict_types=1);

namespace App\Livewire\Admin\StaticPages;

use App\Livewire\Admin\Support\ResourceManageComponent;
use App\Models\StaticPage;
use Livewire\Attributes\Title;
use Override;

#[Title('Página estática')]
class Manage extends ResourceManageComponent
{
    public function mount(?StaticPage $staticPage = null): void
    {
        $this->initRecord($staticPage);
    }

    protected function model(): string
    {
        return StaticPage::class;
    }

    protected function fields(): array
    {
        return [
            'name' => ['label' => __('msg_static_pages.label_name')],
            'slug' => ['label' => __('msg_static_pages.label_slug'), 'unique' => true],
            'content' => ['label' => __('msg_static_pages.label_content'), 'type' => 'richtext', 'rules' => ['required', 'string']],
        ];
    }

    protected function singular(): string
    {
        return __('msg_static_pages.singular');
    }

    protected function indexRoute(): string
    {
        return 'admin.static-pages.index';
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
