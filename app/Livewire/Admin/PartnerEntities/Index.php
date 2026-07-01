<?php

declare(strict_types=1);

namespace App\Livewire\Admin\PartnerEntities;

use App\Enum\Status;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Admin\Support\InteractsWithAddress;
use App\Livewire\Admin\Support\WithDataTable;
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
    use HasAdminActions, InteractsWithAddress, WithDataTable, WithFileUploads, WithPagination;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $activity_carried_out = '';

    public $image;

    public ?string $currentImage = null;

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
        $this->notify($editing ? __('msg_partner_entities.entity_updated') : __('msg_partner_entities.entity_created'));
    }

    public function view(int $id): void
    {
        $entity = PartnerEntity::query()->with(['address.state', 'address.city'])->findOrFail($id);
        $this->viewData = [
            ['label' => __('msg_partner_entities.label_name'), 'value' => $entity->name],
            ['label' => __('msg_partner_entities.label_email'), 'value' => $entity->email],
            ['label' => __('msg_partner_entities.label_phone'), 'value' => $entity->phone],
            ['label' => __('msg_partner_entities.label_activity'), 'value' => $entity->activity_carried_out],
            ['label' => __('msg_partner_entities.label_city_uf'), 'value' => ($entity->address?->city?->name ?? '') . '/' . ($entity->address?->state?->abbr ?? '')],
            ['label' => __('msg_partner_entities.label_status'), 'value' => $entity->status?->label()],
        ];
        $this->viewTitle = $entity->name;
        $this->showView = true;
    }

    public function toggleStatus(int $id): void
    {
        $entity = PartnerEntity::query()->findOrFail($id);
        $entity->update(['status' => $entity->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE]);
        $this->notify(__('msg_partner_entities.status_updated'));
    }

    public function delete(int $id): void
    {
        PartnerEntity::query()->findOrFail($id)->delete();
        $this->notify(__('msg_partner_entities.entity_deleted'));
    }

    public function render(): Factory|View
    {
        $queryBase = PartnerEntity::query()
            ->with(['address.city:id,name', 'address.state:id,name,abbr']);

        $entities = $this->applyTable($queryBase, ['name', 'email']);

        return view('livewire.admin.partner-entities.index', [
            'entities' => $entities,
            'states' => $this->statesOptions(),
            'cities' => $this->citiesOptions(),
        ]);
    }

    /**
     * @return array<int, string>
     */
    protected function sortableColumns(): array
    {
        return ['id', 'name', 'email', 'phone', 'status', 'created_at'];
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
