<?php

declare(strict_types=1);

namespace App\Livewire\Admin\ExternalLinks;

use App\Enum\Status;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Admin\Support\WithDataTable;
use App\Models\ExternalLink;
use App\Models\TypeExternalLink;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Links Externos')]
class Index extends Component
{
    use HasAdminActions, WithDataTable, WithPagination;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $slug = '';

    public ?int $type_external_link_id = null;

    public string $url = '';

    public string $description = '';

    public function create(): void
    {
        $this->reset(['editingId', 'title', 'slug', 'type_external_link_id', 'url', 'description']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $link = ExternalLink::query()->findOrFail($id);
        $this->editingId = $link->id;
        $this->title = $link->title;
        $this->slug = $link->slug;
        $this->type_external_link_id = $link->type_external_link_id;
        $this->url = $link->url;
        $this->description = $link->description;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'title' => $this->title,
            'slug' => Str::slug($this->slug ?: $this->title),
            'type_external_link_id' => $this->type_external_link_id,
            'url' => $this->url,
            'description' => $this->description,
        ];

        if (!$this->editingId) {
            $payload['user_id'] = auth()->id();
            $payload['status'] = Status::ACTIVE;
        }

        $editing = (bool) $this->editingId;

        if ($editing) {
            ExternalLink::query()->whereKey($this->editingId)->update($payload);
        } else {
            ExternalLink::query()->create($payload);
        }

        $this->showModal = false;
        $this->notify($editing ? __('msg_external_links.notify_updated') : __('msg_external_links.notify_created'));
    }

    public function view(int $id): void
    {
        $link = ExternalLink::query()->with('type:id,name')->findOrFail($id);
        $this->viewData = [
            ['label' => __('msg_external_links.label_title'), 'value' => $link->title],
            ['label' => __('msg_external_links.label_type'), 'value' => $link->type?->name],
            ['label' => __('msg_external_links.label_url'), 'value' => $link->url],
            ['label' => __('msg_external_links.label_description'), 'value' => $link->description],
            ['label' => __('msg_external_links.label_status'), 'value' => $link->status?->label()],
        ];
        $this->viewTitle = $link->title;
        $this->showView = true;
    }

    public function toggleStatus(int $id): void
    {
        $link = ExternalLink::query()->findOrFail($id);
        $link->update(['status' => $link->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE]);
        $this->notify(__('msg_external_links.notify_status_updated'));
    }

    public function delete(int $id): void
    {
        ExternalLink::query()->findOrFail($id)->delete();
        $this->notify(__('msg_external_links.notify_deleted'));
    }

    public function render(): Factory|View
    {
        $queryBase = ExternalLink::query()
            ->with('type:id,name');

        $links = $this->applyTable($queryBase, ['title', 'url']);

        return view('livewire.admin.external-links.index', [
            'links' => $links,
            'types' => TypeExternalLink::query()->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    protected function sortableColumns(): array
    {
        return ['id', 'title', 'url', 'status', 'created_at'];
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', Rule::unique('external_links', 'slug')->ignore($this->editingId)],
            'type_external_link_id' => ['required', 'exists:type_external_links,id'],
            'url' => ['required', 'url'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }
}
