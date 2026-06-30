<?php

declare(strict_types=1);

namespace App\Livewire\Admin\PartnerEntities;

use App\Enum\Status;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Admin\Support\InteractsWithAddress;
use App\Models\PartnerEntity;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Entidades Parceiras')]
class Index extends Component
{
    use HasAdminActions, InteractsWithAddress, WithFileUploads, WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $activity_carried_out = '';

    public $image;

    public ?string $currentImage = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'email', 'phone', 'activity_carried_out', 'image', 'currentImage']);
        $this->resetAddress();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $entity = PartnerEntity::query()->with('address')->findOrFail($id);
        $this->editingId = $entity->id;
        $this->name = $entity->name;
        $this->email = $entity->email;
        $this->phone = $entity->phone;
        $this->activity_carried_out = $entity->activity_carried_out;
        $this->currentImage = $entity->path_image;
        $this->image = null;
        $this->fillAddressFrom($entity->address);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $address = $this->persistAddress();

        $payload = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => preg_replace('/\D/', '', $this->phone),
            'activity_carried_out' => $this->activity_carried_out,
            'address_id' => $address->id,
        ];

        if ($this->image) {
            $payload['path_image'] = $this->image->store('partners', 'public');
        }

        if (!$this->editingId) {
            $payload['status'] = Status::ACTIVE;
            $payload['user_id'] = auth()->id();
        }

        $editing = (bool) $this->editingId;

        if ($editing) {
            PartnerEntity::query()->whereKey($this->editingId)->update($payload);
        } else {
            PartnerEntity::query()->create($payload);
        }

        $this->showModal = false;
        $this->notify($editing ? 'Entidade atualizada.' : 'Entidade criada.');
    }

    public function view(int $id): void
    {
        $entity = PartnerEntity::query()->with(['address.state', 'address.city'])->findOrFail($id);
        $this->viewData = [
            ['label' => 'Nome', 'value' => $entity->name],
            ['label' => 'E-mail', 'value' => $entity->email],
            ['label' => 'Telefone', 'value' => $entity->phone],
            ['label' => 'Atividade', 'value' => $entity->activity_carried_out],
            ['label' => 'Cidade/UF', 'value' => ($entity->address?->city?->name ?? '') . '/' . ($entity->address?->state?->abbr ?? '')],
            ['label' => 'Status', 'value' => $entity->status?->label()],
        ];
        $this->viewTitle = $entity->name;
        $this->showView = true;
    }

    public function toggleStatus(int $id): void
    {
        $entity = PartnerEntity::query()->findOrFail($id);
        $entity->update(['status' => $entity->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE]);
        $this->notify('Status atualizado.');
    }

    public function delete(int $id): void
    {
        PartnerEntity::query()->findOrFail($id)->delete();
        $this->notify('Entidade excluída.');
    }

    public function render(): Factory|View
    {
        $entities = PartnerEntity::query()
            ->with(['address.city:id,name', 'address.state:id,name'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.admin.partner-entities.index', [
            'entities' => $entities,
            'states' => $this->statesOptions(),
            'cities' => $this->citiesOptions(),
        ]);
    }

    protected function rules(): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'activity_carried_out' => ['required', 'string'],
            'image' => [$this->editingId ? 'nullable' : 'required', 'image', 'max:4096'],
        ], $this->addressRules());
    }
}
