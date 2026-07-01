<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages\Terreiros;

use App\Actions\Address\FillAddressAction;
use App\Enum\SuggestionID;
use App\Livewire\Forms\TerreiroForm;
use App\Models\Address;
use App\Models\City;
use App\Models\NationsTerreiro;
use App\Models\State;
use App\Models\Suggestion;
use App\Models\Terreiro;
use App\Models\TerreiroQuestion;
use App\Models\TypePeople;
use App\Traits\Utils;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Sleep;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    public TerreiroForm $form;

    public int $currentStep = 1;

    public $nations;

    public $states;

    public $cities;

    public $typePeoples;

    public $suggestions;

    public bool $showField = false;

    public function mount(): void
    {
        $this->nations = Cache::remember('all_nations', 60 * 60 * 24, fn () => NationsTerreiro::query()
            ->select('id', 'name')
            ->get());

        $this->states = Cache::remember('all_brazilian_states', 60 * 60 * 24, fn () => State::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get());

        $this->cities = collect();

        if ($this->form->state_id) {
            $this->loadCities($this->form->state_id);
        }

        $this->typePeoples = Cache::remember('all_type_people', 60 * 60 * 24, fn () => TypePeople::query()
            ->select('id', 'name')
            ->get());

        $this->suggestions = Cache::remember('suggestions_cantin_terreiro', 60 * 60 * 24, fn () => Suggestion::query()
            ->select(['id', 'name', 'slug'])
            ->get());
    }

    public function updated(string $property, $value): void
    {
        if (!str_starts_with($property, 'form.')) {
            return;
        }

        $field = mb_substr($property, mb_strlen('form.'));

        // Cascata estado -> cidade: limpa a cidade e recarrega a lista.
        if ($field === 'state_id') {
            $this->form->validateOnly('state_id');

            $this->form->city_id = null;
            $this->cities = collect();

            if ($value) {
                $this->loadCities((int) $value);
            }

            return;
        }

        // Mostra o campo de texto livre só para a sugestão "Outro".
        if ($field === 'suggestion_id') {
            $this->showField = SuggestionID::verifySuggestionId((string) $value);
            $this->form->suggestion_text = '';
        }

        $this->form->validateOnly($field);
    }

    public function nextStep(): void
    {
        $this->currentStep++;
    }

    public function previousStep(): void
    {
        $this->currentStep--;
    }

    public function searchZipCode(): void
    {
        $cleanedZipCode = preg_replace('/\D/', '', $this->form->zipcode);

        if (!preg_match('/^\d{8}$/', (string) $cleanedZipCode)) {
            $this->form->addError('zipcode', __('Invalid zipcode!'));

            return;
        }

        // Mesma lógica robusta do dashboard (InteractsWithAddress::buscarCep):
        // o FillAddressAction já retorna state_id/city_id resolvidos.
        try {
            $data = FillAddressAction::exec($cleanedZipCode);

            $this->form->street = $data->address ?? '';
            $this->form->neighborhood = $data->neighborhood ?? '';
            $this->form->complement = $data->complement ?? '';
            $this->form->state_id = $data->state;

            if ($this->form->state_id) {
                $this->loadCities($this->form->state_id);
            }

            $this->form->city_id = $data->city;
        } catch (Throwable $e) {
            // Reporta ao Telegram (canal de log) e avisa o usuário no campo.
            Log::error('Erro na busca de CEP (site): ' . $e->getMessage(), ['zipcode' => $cleanedZipCode]);
            $this->form->addError('zipcode', __('Error when searching for zip code!'));
        }
    }

    public function store(): void
    {
        try {
            $this->form->validate();
        } catch (ValidationException $e) {
            toastr()
                ->timeOut(4000)
                ->error(__('Please fill in the required fields!'));

            throw $e;
        }

        try {
            DB::beginTransaction();

            $clearZipCode = str($this->form->zipcode)->replace('-', '');

            $address = Address::query()
                ->where('zipcode', '=', $clearZipCode)
                ->first();

            if (!$address) {
                $address = Address::query()->create([
                    'zipcode' => $clearZipCode,
                    'address' => $this->form->street,
                    'complement' => $this->form->complement,
                    'neighborhood' => $this->form->neighborhood,
                    'state_id' => $this->form->state_id,
                    'city_id' => $this->form->city_id,
                ]);
            }

            $terreiro = Terreiro::query()->create([
                'name' => $this->form->name,
                'nation_terreiro_id' => $this->form->nation_terreiro_id,
                'phone' => Utils::clearMask($this->form->phone),
                'leadership_orunko' => $this->form->leadership_orunko,
                'color_of_leadership' => $this->form->color_of_leadership,
                'address_id' => $address->id,
            ]);

            TerreiroQuestion::query()->create([
                'terreiro_id' => $terreiro->id,
                'type_people_id' => $this->form->type_people_id,
                'number_of_children_of_saint' => $this->form->number_of_children_of_saint,
                'number_of_children_of_saint_trans' => $this->form->number_of_children_of_saint_trans,
                'trans_men_and_women' => $this->form->trans_men_and_women,
                'name_gender' => $this->form->name_gender,
                'fully_welcomes' => $this->form->fully_welcomes,
                'respect_for_trans_people' => $this->form->respect_for_trans_people,
                'suffered_aggregation' => $this->form->suffered_aggregation,
                'inclusion_of_the_name_of_the_land' => $this->form->inclusion_of_the_name_of_the_land,
                'suggestion_id' => $this->form->suggestion_id,
                'suggestion_text' => $this->form->suggestion_text,
            ]);
            DB::commit();

            Sleep::sleep(3);

            toastr()
                ->timeOut(2000)
                ->success(__('Terreiro successfully registered!'));

            $this->redirectRoute('site.terreiros.search');
        } catch (Exception|Throwable $e) {
            DB::rollBack();
            Utils::webhook('error', $e, 'Error when creating terreiro');
            Log::error($e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            toastr()
                ->timeOut(2000)
                ->error(__('Error when creating terreiro!'));
        }
    }

    public function render(): Factory|View
    {
        return view('livewire.site.pages.terreiros.create');
    }

    protected function loadCities(int $stateId): void
    {
        // Sem cache: o conjunto de cidades pode mudar (re-seed IBGE) e o cache
        // por state_id ficaria obsoleto.
        $this->cities = City::query()
            ->select('id', 'name')
            ->where('state_id', '=', $stateId)
            ->orderBy('name')
            ->get();
    }
}
