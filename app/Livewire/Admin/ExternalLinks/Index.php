<?php

namespace App\Livewire\Admin\ExternalLinks;

use App\Enum\Status;
use App\Models\ExternalLink;
use App\Models\TypeExternalLink;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Links Externos')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $slug = '';

    public ?int $type_external_link_id = null;

    public string $url = '';

    public string $description = '';

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

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

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
            'slug' => \Illuminate\Support\Str::slug($this->slug ?: $this->title),
            'type_external_link_id' => $this->type_external_link_id,
            'url' => $this->url,
            'description' => $this->description,
        ];

        if (! $this->editingId) {
            $payload['user_id'] = auth()->id();
            $payload['status'] = Status::ACTIVE;
        }

        ExternalLink::query()->updateOrCreate(['id' => $this->editingId], $payload);

        $this->showModal = false;
        session()->flash('status', $this->editingId ? 'Link atualizado.' : 'Link criado.');
    }

    public function toggleStatus(int $id): void
    {
        $link = ExternalLink::query()->findOrFail($id);
        $link->update(['status' => $link->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE]);
    }

    public function delete(int $id): void
    {
        ExternalLink::query()->findOrFail($id)->delete();
        session()->flash('status', 'Link excluído.');
    }

    public function render()
    {
        $links = ExternalLink::query()
            ->with('type:id,name')
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%")->orWhere('url', 'like', "%{$this->search}%"))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.admin.external-links.index', [
            'links' => $links,
            'types' => TypeExternalLink::query()->orderBy('name')->pluck('name', 'id'),
        ]);
    }
}
