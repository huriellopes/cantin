<?php

declare(strict_types=1);

namespace App\Livewire\Admin\TransPeoples;

use App\Enum\Status;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Admin\Support\WithDataTable;
use App\Livewire\Forms\TransPersonForm;
use App\Models\TransPeople;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Pessoas Trans')]
class Index extends Component
{
    use HasAdminActions, WithDataTable, WithPagination;

    public bool $showModal = false;

    public TransPersonForm $form;

    public function buscarCep(): void
    {
        $this->form->buscarCep();
    }

    public function create(): void
    {
        $this->form->reset();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $person = TransPeople::query()->with('address')->findOrFail($id);
        $this->form->editingId = $person->id;
        $this->form->name = $person->name;
        $this->form->email = $person->email;
        $this->form->phone = $person->phone;
        $this->form->fillAddressFrom($person->address);
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
            'address_id' => $address->id,
        ];

        if (!$this->form->editingId) {
            $payload['status'] = Status::ACTIVE;
        }

        $editing = (bool) $this->form->editingId;

        if ($editing) {
            TransPeople::query()->whereKey($this->form->editingId)->update($payload);
        } else {
            TransPeople::query()->create($payload);
        }

        $this->showModal = false;
        $this->notify($editing ? __('msg_trans_peoples.cadastro_atualizado') : __('msg_trans_peoples.pessoa_cadastrada'));
    }

    public function view(int $id): void
    {
        $person = TransPeople::query()->with(['address.state', 'address.city'])->findOrFail($id);
        $this->viewData = [
            ['label' => __('msg_trans_peoples.label_nome'), 'value' => $person->name],
            ['label' => __('msg_trans_peoples.label_email'), 'value' => $person->email],
            ['label' => __('msg_trans_peoples.label_telefone'), 'value' => $person->phone],
            ['label' => __('msg_trans_peoples.label_cidade_uf'), 'value' => ($person->address?->city?->name ?? '') . '/' . ($person->address?->state?->abbr ?? '')],
            ['label' => __('msg_trans_peoples.label_status'), 'value' => $person->status?->label()],
        ];
        $this->viewTitle = $person->name;
        $this->showView = true;
    }

    public function toggleStatus(int $id): void
    {
        $person = TransPeople::query()->findOrFail($id);
        $person->update(['status' => $person->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE]);
        $this->notify(__('msg_trans_peoples.status_atualizado'));
    }

    public function delete(int $id): void
    {
        TransPeople::query()->findOrFail($id)->delete();
        $this->notify(__('msg_trans_peoples.cadastro_excluido'));
    }

    public function render(): Factory|View
    {
        $queryBase = TransPeople::query()
            ->with(['address.city:id,name', 'address.state:id,name,abbr']);

        $people = $this->applyTable($queryBase, ['name', 'email']);

        return view('livewire.admin.trans-peoples.index', [
            'people' => $people,
            'states' => $this->form->statesOptions(),
            'cities' => $this->form->citiesOptions(),
        ]);
    }

    protected function sortableColumns(): array
    {
        return ['id', 'name', 'email', 'status', 'created_at'];
    }
}
