<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Terreiros;

use App\Exports\TerreirosExport;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Admin\Support\WithDataTable;
use App\Livewire\Forms\TerreiroAdminForm;
use App\Models\NationsTerreiro;
use App\Models\Terreiro;
use App\Models\TypePeople;
use App\Support\ExportManager;
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
    use HasAdminActions, WithDataTable, WithPagination;

    public bool $showModal = false;

    public TerreiroAdminForm $form;

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
        $terreiro = Terreiro::query()->with(['address', 'question'])->findOrFail($id);
        $this->form->editingId = $terreiro->id;
        $this->form->name = $terreiro->name;
        $this->form->phone = $terreiro->phone;
        $this->form->nation_terreiro_id = $terreiro->nation_terreiro_id;
        $this->form->leadership_orunko = $terreiro->leadership_orunko;
        $this->form->color_of_leadership = $terreiro->color_of_leadership;

        $this->form->fillAddressFrom($terreiro->address);

        if ($terreiro->question) {
            $this->form->type_people_id = $terreiro->question->type_people_id;
            $this->form->number_of_children_of_saint = $terreiro->question->number_of_children_of_saint;
            $this->form->number_of_children_of_saint_trans = $terreiro->question->number_of_children_of_saint_trans;
            $this->form->trans_men_and_women = $terreiro->question->trans_men_and_women;
            $this->form->name_gender = $terreiro->question->name_gender;
            $this->form->fully_welcomes = $terreiro->question->fully_welcomes;
            $this->form->respect_for_trans_people = $terreiro->question->respect_for_trans_people;
            $this->form->suffered_aggregation = $terreiro->question->suffered_aggregation;
            $this->form->inclusion_of_the_name_of_the_land = $terreiro->question->inclusion_of_the_name_of_the_land;
            $this->form->suggestion_id = $terreiro->question->suggestion_id;
            $this->form->suggestion_text = $terreiro->question->suggestion_text;
        }

        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->form->validate();

        DB::transaction(function (): void {
            $address = $this->form->persistAddress();

            $terreiroData = [
                'name' => $this->form->name,
                'phone' => preg_replace('/\D/', '', $this->form->phone),
                'nation_terreiro_id' => $this->form->nation_terreiro_id,
                'leadership_orunko' => $this->form->leadership_orunko,
                'color_of_leadership' => $this->form->color_of_leadership,
                'address_id' => $address->id,
            ];

            if ($this->form->editingId) {
                $terreiro = Terreiro::query()->findOrFail($this->form->editingId);
                $terreiro->update($terreiroData);
            } else {
                $terreiro = Terreiro::query()->create($terreiroData);
            }

            $terreiro->question()->updateOrCreate(
                ['terreiro_id' => $terreiro->id],
                $this->form->questionData(),
            );

            $this->form->editingId = $terreiro->id;
        });

        $this->showModal = false;
        $this->notify(__('msg_terreiros.terreiro_saved'));
    }

    public function view(int $id): void
    {
        $terreiro = Terreiro::query()->with(['nation', 'address.state', 'address.city'])->findOrFail($id);
        $this->viewData = [
            ['label' => __('msg_terreiros.label_name'), 'value' => $terreiro->name],
            ['label' => __('msg_terreiros.label_phone'), 'value' => $terreiro->phone],
            ['label' => __('msg_terreiros.label_leadership'), 'value' => $terreiro->leadership_orunko],
            ['label' => __('msg_terreiros.label_nation'), 'value' => $terreiro->nation?->name],
            ['label' => __('msg_terreiros.label_color_of_leadership'), 'value' => $terreiro->color_of_leadership],
            ['label' => __('msg_terreiros.label_address'), 'value' => mb_trim(($terreiro->address?->address ?? '') . ' — ' . ($terreiro->address?->city?->name ?? '') . '/' . ($terreiro->address?->state?->abbr ?? ''))],
            ['label' => __('msg_terreiros.label_zipcode'), 'value' => $terreiro->address?->zipcode],
        ];
        $this->viewTitle = $terreiro->name;
        $this->showView = true;
    }

    public function delete(int $id): void
    {
        Terreiro::query()->findOrFail($id)->delete();
        $this->notify(__('msg_terreiros.terreiro_deleted'));
    }

    public function export(): void
    {
        ExportManager::dispatch(TerreirosExport::class, __('admin.nav.terreiros'));
        $this->dispatch('toast', type: 'info', message: __('exports.started'));
    }

    public function render(): Factory|View
    {
        $terreiros = $this->applyTable(
            Terreiro::query()
                ->with(['nation:id,name', 'address.state:id,name', 'address.city:id,name']),
            ['name'],
        );

        return view('livewire.admin.terreiros.index', [
            'terreiros' => $terreiros,
            'nations' => NationsTerreiro::query()->orderBy('name')->pluck('name', 'id'),
            'typePeoples' => TypePeople::query()->orderBy('name')->pluck('name', 'id'),
            'states' => $this->form->statesOptions(),
            'cities' => $this->form->citiesOptions(),
            'config' => config('terreiro'),
        ]);
    }

    protected function sortableColumns(): array
    {
        return ['id', 'name', 'phone', 'leadership_orunko'];
    }
}
