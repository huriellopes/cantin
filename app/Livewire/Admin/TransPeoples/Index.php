<?php

namespace App\Livewire\Admin\TransPeoples;

use App\Enum\Status;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Admin\Support\InteractsWithAddress;
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
    use HasAdminActions, InteractsWithAddress, WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    protected function rules(): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
        ], $this->addressRules());
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'email', 'phone']);
        $this->resetAddress();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $person = TransPeople::query()->with('address')->findOrFail($id);
        $this->editingId = $person->id;
        $this->name = $person->name;
        $this->email = $person->email;
        $this->phone = $person->phone;
        $this->fillAddressFrom($person->address);
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
            'address_id' => $address->id,
        ];

        if (! $this->editingId) {
            $payload['status'] = Status::ACTIVE;
        }

        $editing = (bool) $this->editingId;

        if ($editing) {
            TransPeople::query()->whereKey($this->editingId)->update($payload);
        } else {
            TransPeople::query()->create($payload);
        }

        $this->showModal = false;
        $this->notify($editing ? 'Cadastro atualizado.' : 'Pessoa cadastrada.');
    }

    public function view(int $id): void
    {
        $person = TransPeople::query()->with(['address.state', 'address.city'])->findOrFail($id);
        $this->viewData = [
            ['label' => 'Nome', 'value' => $person->name],
            ['label' => 'E-mail', 'value' => $person->email],
            ['label' => 'Telefone', 'value' => $person->phone],
            ['label' => 'Cidade/UF', 'value' => ($person->address?->city?->name ?? '').'/'.($person->address?->state?->abbr ?? '')],
            ['label' => 'Status', 'value' => $person->status?->label()],
        ];
        $this->viewTitle = $person->name;
        $this->showView = true;
    }

    public function toggleStatus(int $id): void
    {
        $person = TransPeople::query()->findOrFail($id);
        $person->update(['status' => $person->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE]);
        $this->notify('Status atualizado.');
    }

    public function delete(int $id): void
    {
        TransPeople::query()->findOrFail($id)->delete();
        $this->notify('Cadastro excluído.');
    }

    public function render(): Factory|View
    {
        $people = TransPeople::query()
            ->with(['address.city:id,name', 'address.state:id,name'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.admin.trans-peoples.index', [
            'people' => $people,
            'states' => $this->statesOptions(),
            'cities' => $this->citiesOptions(),
        ]);
    }
}
