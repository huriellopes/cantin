<?php

namespace App\Livewire\Admin\Terreiros;

use App\Actions\Address\FillAddressAction;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Models\Address;
use App\Models\City;
use App\Models\NationsTerreiro;
use App\Models\State;
use App\Models\Terreiro;
use App\Models\TypePeople;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Terreiros')]
class Index extends Component
{
    use HasAdminActions, WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    // Dados do terreiro
    public string $name = '';

    public string $phone = '';

    public ?int $nation_terreiro_id = null;

    public string $leadership_orunko = '';

    public string $color_of_leadership = '';

    // Endereço
    public string $zipcode = '';

    public string $address = '';

    public string $complement = '';

    public string $neighborhood = '';

    public ?int $state_id = null;

    public ?int $city_id = null;

    public ?string $latitude = null;

    public ?string $longitude = null;

    // Questionário
    public ?int $type_people_id = null;

    public ?string $number_of_children_of_saint = null;

    public ?string $number_of_children_of_saint_trans = null;

    public ?string $trans_men_and_women = null;

    public ?string $name_gender = null;

    public ?string $fully_welcomes = null;

    public ?string $respect_for_trans_people = null;

    public ?string $suffered_aggregation = null;

    public ?string $inclusion_of_the_name_of_the_land = null;

    public ?int $suggestion_id = null;

    public ?string $suggestion_text = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string'],
            'nation_terreiro_id' => ['required', 'exists:nations_terreiros,id'],
            'leadership_orunko' => ['required', 'string', 'max:255'],
            'color_of_leadership' => ['required', 'string'],
            'zipcode' => ['required', 'string'],
            'address' => ['required', 'string'],
            'neighborhood' => ['required', 'string'],
            'state_id' => ['required', 'exists:states,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'type_people_id' => ['required', 'exists:type_peoples,id'],
            'number_of_children_of_saint' => ['required'],
            'number_of_children_of_saint_trans' => ['required'],
            'trans_men_and_women' => ['required'],
            'name_gender' => ['required'],
            'fully_welcomes' => ['required'],
            'respect_for_trans_people' => ['required'],
            'suffered_aggregation' => ['required'],
            'inclusion_of_the_name_of_the_land' => ['required'],
            'suggestion_id' => ['required'],
            'suggestion_text' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function buscarCep(): void
    {
        try {
            $data = FillAddressAction::exec(preg_replace('/\D/', '', $this->zipcode));
            $this->address = $data->address ?? $this->address;
            $this->neighborhood = $data->neighborhood ?? $this->neighborhood;
            $this->complement = $data->complement ?? $this->complement;
            $this->state_id = $data->state ?? $this->state_id;
            $this->city_id = $data->city ?? $this->city_id;
            $this->latitude = $data->latitude ?? null;
            $this->longitude = $data->longitude ?? null;
        } catch (\Throwable) {
            $this->addError('zipcode', 'Não foi possível buscar o CEP.');
        }
    }

    public function create(): void
    {
        $this->reset(array_keys($this->formFields()));
        $this->editingId = null;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $terreiro = Terreiro::query()->with(['address', 'question'])->findOrFail($id);
        $this->editingId = $terreiro->id;
        $this->name = $terreiro->name;
        $this->phone = $terreiro->phone;
        $this->nation_terreiro_id = $terreiro->nation_terreiro_id;
        $this->leadership_orunko = $terreiro->leadership_orunko;
        $this->color_of_leadership = $terreiro->color_of_leadership;

        if ($terreiro->address) {
            $this->fill($terreiro->address->only([
                'zipcode', 'address', 'complement', 'neighborhood', 'state_id', 'city_id', 'latitude', 'longitude',
            ]));
        }

        if ($terreiro->question) {
            $this->fill($terreiro->question->only(array_keys($this->questionFields())));
        }

        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function (): void {
            $address = Address::query()->updateOrCreate(
                ['zipcode' => $this->zipcode],
                [
                    'address' => $this->address,
                    'complement' => $this->complement,
                    'neighborhood' => $this->neighborhood,
                    'state_id' => $this->state_id,
                    'city_id' => $this->city_id,
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude,
                ]
            );

            $terreiroData = [
                'name' => $this->name,
                'phone' => preg_replace('/\D/', '', $this->phone),
                'nation_terreiro_id' => $this->nation_terreiro_id,
                'leadership_orunko' => $this->leadership_orunko,
                'color_of_leadership' => $this->color_of_leadership,
                'address_id' => $address->id,
            ];

            if ($this->editingId) {
                $terreiro = Terreiro::query()->findOrFail($this->editingId);
                $terreiro->update($terreiroData);
            } else {
                $terreiro = Terreiro::query()->create($terreiroData);
            }

            $terreiro->question()->updateOrCreate(
                ['terreiro_id' => $terreiro->id],
                $this->only(array_keys($this->questionFields()))
            );

            $this->editingId = $terreiro->id;
        });

        $this->showModal = false;
        $this->notify('Terreiro salvo com sucesso.');
    }

    public function view(int $id): void
    {
        $terreiro = Terreiro::query()->with(['nation', 'address.state', 'address.city'])->findOrFail($id);
        $this->viewData = [
            ['label' => 'Nome', 'value' => $terreiro->name],
            ['label' => 'Telefone', 'value' => $terreiro->phone],
            ['label' => 'Liderança', 'value' => $terreiro->leadership_orunko],
            ['label' => 'Nação', 'value' => $terreiro->nation?->name],
            ['label' => 'Cor da liderança', 'value' => $terreiro->color_of_leadership],
            ['label' => 'Endereço', 'value' => trim(($terreiro->address?->address ?? '').' — '.($terreiro->address?->city?->name ?? '').'/'.($terreiro->address?->state?->abbr ?? ''))],
            ['label' => 'CEP', 'value' => $terreiro->address?->zipcode],
        ];
        $this->viewTitle = $terreiro->name;
        $this->showView = true;
    }

    public function delete(int $id): void
    {
        Terreiro::query()->findOrFail($id)->delete();
        $this->notify('Terreiro excluído.');
    }

    public function exportCsv()
    {
        $filename = 'terreiros-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Nome', 'Telefone', 'Nação', 'CEP', 'Endereço', 'Liderança', 'Cor da liderança', 'Criado em']);

            Terreiro::query()->with(['nation', 'address'])->orderBy('id')
                ->chunk(200, function ($terreiros) use ($out): void {
                    foreach ($terreiros as $terreiro) {
                        fputcsv($out, [
                            $terreiro->id,
                            $terreiro->name,
                            $terreiro->phone,
                            $terreiro->nation?->name,
                            $terreiro->address?->zipcode,
                            $terreiro->address?->address,
                            $terreiro->leadership_orunko,
                            $terreiro->color_of_leadership,
                            $terreiro->created_at?->format('d/m/Y H:i'),
                        ]);
                    }
                });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function questionFields(): array
    {
        return [
            'type_people_id' => null,
            'number_of_children_of_saint' => null,
            'number_of_children_of_saint_trans' => null,
            'trans_men_and_women' => null,
            'name_gender' => null,
            'fully_welcomes' => null,
            'respect_for_trans_people' => null,
            'suffered_aggregation' => null,
            'inclusion_of_the_name_of_the_land' => null,
            'suggestion_id' => null,
            'suggestion_text' => null,
        ];
    }

    private function formFields(): array
    {
        return array_merge([
            'name' => '', 'phone' => '', 'nation_terreiro_id' => null, 'leadership_orunko' => '',
            'color_of_leadership' => '', 'zipcode' => '', 'address' => '', 'complement' => '',
            'neighborhood' => '', 'state_id' => null, 'city_id' => null, 'latitude' => null, 'longitude' => null,
        ], $this->questionFields());
    }

    public function render(): Factory|View
    {
        $terreiros = Terreiro::query()
            ->with(['nation:id,name', 'address.state:id,name', 'address.city:id,name'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.admin.terreiros.index', [
            'terreiros' => $terreiros,
            'nations' => NationsTerreiro::query()->orderBy('name')->pluck('name', 'id'),
            'typePeoples' => TypePeople::query()->orderBy('name')->pluck('name', 'id'),
            'states' => State::query()->orderBy('name')->pluck('name', 'id'),
            'cities' => $this->state_id
                ? City::query()->where('state_id', $this->state_id)->orderBy('name')->pluck('name', 'id')
                : collect(),
            'config' => config('terreiro'),
        ]);
    }
}
