<?php

declare(strict_types=1);

namespace App\Livewire\Admin\PartnerEntities;

use App\Enum\Status;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Admin\Support\WithDataTable;
use App\Livewire\Forms\PartnerEntityAdminForm;
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
    use HasAdminActions, WithDataTable, WithFileUploads, WithPagination;

    public bool $showModal = false;

    public PartnerEntityAdminForm $form;

    public ?string $currentImage = null;

    public function buscarCep(): void
    {
        $this->form->buscarCep();
    }

    public function create(): void
    {
        $this->form->reset();
        $this->currentImage = null;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $entity = PartnerEntity::query()->with('address')->findOrFail($id);
        $this->form->editingId = $entity->id;
        $this->form->name = $entity->name;
        $this->form->email = $entity->email;
        $this->form->phone = $entity->phone;
        $this->form->activity_carried_out = $entity->activity_carried_out;
        $this->currentImage = $entity->path_image;
        $this->form->image = null;
        $this->form->fillAddressFrom($entity->address);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->form->validate();

        $address = $this->form->persistAddress();

        $payload = [
            'name' => $this->form->name,
            'email' => $this->form->email,
            'phone' => preg_replace('/\D/', '', $this->form->phone),
            'activity_carried_out' => $this->form->activity_carried_out,
            'address_id' => $address->id,
        ];

        if ($this->form->image) {
            $payload['path_image'] = $this->form->image->store('partners', 'public');
        }

        if (!$this->form->editingId) {
            $payload['status'] = Status::ACTIVE;
            $payload['user_id'] = auth()->id();
        }

        $editing = (bool) $this->form->editingId;

        if ($editing) {
            PartnerEntity::query()->whereKey($this->form->editingId)->update($payload);
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
            'states' => $this->form->statesOptions(),
            'cities' => $this->form->citiesOptions(),
        ]);
    }

    /**
     * @return array<int, string>
     */
    protected function sortableColumns(): array
    {
        return ['id', 'name', 'email', 'phone', 'status', 'created_at'];
    }
}
