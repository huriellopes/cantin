<?php

namespace App\Livewire\Site\Pages\Terreiros;

use App\Actions\Address\FillAddressAction;
use App\Enum\SuggestionID;
use App\Models\Address;
use App\Models\City;
use App\Models\NationsTerreiro;
use App\Models\State;
use App\Models\Suggestion;
use App\Models\Terreiro;
use App\Models\TerreiroQuestion;
use App\Models\TypePeople;
use App\Traits\Utils;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Exception;
use Throwable;

class Create extends Component
{
    public int $currentStep = 1;

    public string $name;
    public int $nation_terreiro_id;
    public string $phone;
    public $leadership_orunko;
    public string $color_of_leadership;
    public string $zipcode = '';
    public string $street = '';
    public string $complement = '';
    public ?int $state_id = null;
    public ?int $city_id = null;
    public string $neighborhood = '';
    public $latitude;
    public $longitude;

    public $type_people_id;
    public $number_of_children_of_saint;
    public $number_of_children_of_saint_trans;
    public $trans_men_and_women;
    public $name_gender;
    public $fully_welcomes;
    public $respect_for_trans_people;
    public $suffered_aggregation;
    public $inclusion_of_the_name_of_the_land;
    public $suggestion_id;
    public $suggestion_text;

    public $nations;
    public $states;
    public $cities;
    public $typePeoples;
    public $suggestions;

    public bool $showField = false;

    public function mount(): void
    {
        $this->nations = Cache::remember('all_nations', 60 * 60 * 24, function () {
            return NationsTerreiro::query()
                ->select('id', 'name')
                ->get();
        });

        $this->states = Cache::remember('all_brazilian_states', 60 * 60 * 24, function () {
            return State::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        });

        $this->cities = collect();

        if ($this->state_id) {
            $this->loadCities($this->state_id);
        }

        $this->typePeoples = Cache::remember('all_type_people', 60 * 60 * 24, function () {
            return TypePeople::query()
                ->select('id', 'name')
                ->get();
        });

        $this->suggestions = Cache::remember('suggestions_cantin_terreiro', 60 * 60 * 24, function () {
            return Suggestion::query()
                ->select(['id', 'name', 'slug'])
                ->get();
        });
    }

    /**
     * @param int|null $value
     * @return void
     */
    public function updatedStateId(?int $value): void
    {
        $this->validateOnly('state_id');

        $this->city_id = null;
        $this->cities = null;

        if ($value) {
            $this->loadCities($value);
        }
    }

    /**
     * @param $property
     * @return void
     */
    public function updated($property) : void
    {
        if ($property !== 'state_id') {
            $this->validateOnly($property);
        }
    }

    /**
     * @return void
     */
    public function nextStep(): void
    {
        $this->currentStep++;
    }

    /**
     * @return void
     */
    public function previousStep(): void
    {
        $this->currentStep--;
    }

    public function updatedSuggestionID($option): void
    {
        $this->showField = SuggestionID::verifySuggestionId($option);
        $this->suggestion_text = '';
    }

    protected function loadCities(int $stateId): void
    {
        $cacheKey = 'cities_of_state_' . $stateId;

        $this->cities = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($stateId) {
            return City::query()
                ->select('id', 'name')
                ->where('state_id', '=', $stateId)
                ->orderBy('name')
                ->get();
        });
    }

    public function searchZipCode(): void
    {
        try {
            if (empty($this->zipcode)) {
                toastr()
                    ->timeOut(2000)
                    ->error(__('Invalid zipcode!'));
                return;
            }

            $cleanedZipCode = str($this->zipcode)->replace('-', '');

            if (!preg_match('/^\d{8}$/', $cleanedZipCode)) {
                toastr()
                    ->timeOut(2000)
                    ->error(__('Invalid zipcode!'));
                return;
            }

            $addressExists = FillAddressAction::exec($cleanedZipCode);

            $this->street = $addressExists->address ?? '';
            $this->neighborhood = $addressExists->neighborhood ?? '';
            $this->complement = $addressExists->complement ?? '';
            $this->latitude = $addressExists->latitude ?? null;
            $this->longitude = $addressExists->longitude ?? null;

            $stateModel = State::query()
                ->select('id', 'name', 'abbr')
                ->where('id', '=', $addressExists->state)
                ->first();

            if ($stateModel) {
                $this->state_id = $stateModel->id;
                $this->loadCities($this->state_id);

                $cityModel = City::query()
                    ->select('id', 'name')
                    ->where('id', '=', $addressExists->city)
                    ->where('state_id', '=', $this->state_id)
                    ->first();

                if ($cityModel) {
                    $this->city_id = $cityModel->id;
                } else {
                    $this->city_id = null;
                }
            } else {
                $this->state_id = null;
                $this->city_id = null;
                $this->cities = collect();
            }

        } catch (Exception|Throwable $e) {
            Utils::webhook('error', $e, 'Cep not found', null);

            Log::error($e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            toastr()
                ->timeOut(2000)
                ->error(__('Error when searching for zip code!'));
        }
    }

    /**
     * @return string[]
     */
    protected function rules() : array
    {
        return [
            'name' => 'required|string',
            'nation_terreiro_id' => 'required|integer',
            'phone' => 'required|string',
            'leadership_orunko' => 'required|string',
            'color_of_leadership' => 'required|string',
            'zipcode' => 'required|string',
            'street' => 'required|string',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'type_people_id' => 'required|integer',
            'number_of_children_of_saint' => 'required|integer',
            'number_of_children_of_saint_trans' => 'required|integer',
            'trans_men_and_women' => 'required|string',
            'name_gender' => 'required|string',
            'fully_welcomes' => 'required|string',
            'respect_for_trans_people' => 'required|string',
            'suffered_aggregation' => 'required|string',
            'inclusion_of_the_name_of_the_land' => 'required|string',
            'suggestion_id' => 'nullable|integer',
            'suggestion_text' => 'nullable|string',
        ];
    }

    /**
     * @return array
     */
    protected function messages() : array
    {
        return [
            'name.required' => __('The name field is required.'),
            'name.string' => __('The name field only allows characters.'),
            'nation_terreiro_id.required' => __('The nation field is required.'),
            'nation_terreiro_id.integer' => __('The nation field is only allowed numeric characters.'),
            'email.required' => __('The email field is required.'),
            'email.string' => __('The email field only allows characters.'),
            'email.email' => __('The email field is invalid.'),
            'phone.required' => __('The phone field is required.'),
            'phone.string' => __('The phone field only allows characters.'),
            'leadership_orunko.required' => __('The leadership_orunko field is required.'),
            'leadership_orunko.string' => __('The leadership_orunko field only allows characters.'),
            'color_of_leadership.required' => __('The color_of_leadership field is required.'),
            'color_of_leadership.string' => __('The color_of_leadership field only allows characters.'),
            'zipcode.required' => __('The zipcode field is required.'),
            'zipcode.string' => __('The zipcode field only allows characters.'),
            'street.required' => __('The address field is required.'),
            'street.string' => __('The address field only allows characters.'),
            'complement.string' => __('The complement field only allows characters.'),
            'neighborhood.required' => __('The neighborhood field is required.'),
            'neighborhood.string' => __('The neighborhood field only allows characters.'),
            'state_id.required' => __('The state field is required.'),
            'state_id.integer' => __('The state field is only allowed numeric characters.'),
            'city_id.required' => __('The city field is required.'),
            'city_id.integer' => __('The city field is only allowed numeric characters.'),
            'type_people_id.required' => __('The type_people field is required.'),
            'type_people_id.integer' => __('The type_people field is only allowed numeric characters.'),
            'number_of_children_of_saint.required' => __('The number_of_children_of_saint field is required.'),
            'number_of_children_of_saint.integer' => __('The number_of_children_of_saint field only allowed numeric characters.'),
            'number_of_children_of_saint_trans.required' => __('The number_of_children_of_saint_trans field is required.'),
            'number_of_children_of_saint_trans.integer' => __('The number_of_children_of_saint_trans field only allowed numeric characters.'),
            'trans_men_and_women.required' => __('The trans_men_and_women field is required.'),
            'trans_men_and_women.string' => __('The trans_men_and_women field only allows characters.'),
            'name_gender.required' => __('The name_gender field is required.'),
            'name_gender.string' => __('The name_gender field only allows characters.'),
            'fully_welcomes.required' => __('The fully_welcomes field is required.'),
            'fully_welcomes.string' => __('The fully_welcomes field only allows characters.'),
            'respect_for_trans_people.required' => __('The respect_for_trans_people field is required.'),
            'respect_for_trans_people.string' => __('The respect_for_trans_people field only allows characters.'),
            'suffered_aggregation.required' => __('The suffered_aggregation field is required.'),
            'suffered_aggregation.string' => __('The suffered_aggregation field only allows characters.'),
            'inclusion_of_the_name_of_the_land.required' => __('The inclusion_of_the_name_of_the_land field is required.'),
            'inclusion_of_the_name_of_the_land.string' => __('The inclusion_of_the_name_of_the_land field only allows characters.'),
            'suggestion_id.integer' => __('The suggestion field is only allowed numeric characters.'),
            'suggestion_text.string' => __('The suggestion_text field only allows characters.'),
        ];
    }

    public function store() : void
    {
        try {
            DB::beginTransaction();
                $this->validate();

                $clearZipCode = str($this->zipcode)->replace('-', '');

                $address = Address::query()
                    ->where('zipcode', '=', $clearZipCode)
                    ->first();

                if (!$address) {
                    $address = Address::create([
                        'zipcode' => $clearZipCode,
                        'address' => $this->street,
                        'complement' => $this->complement,
                        'neighborhood' => $this->neighborhood,
                        'state_id' => $this->state_id,
                        'city_id' => $this->city_id,
                    ]);
                }

                $terreiro = Terreiro::create([
                    'name' => $this->name,
                    'nation_terreiro_id' => $this->nation_terreiro_id,
                    'phone' => Utils::clearMask($this->phone),
                    'leadership_orunko' => $this->leadership_orunko,
                    'color_of_leadership' => $this->color_of_leadership,
                    'address_id' => $address->id,
                ]);

                TerreiroQuestion::create([
                    'terreiro_id' => $terreiro->id,
                    'type_people_id' => $this->type_people_id,
                    'number_of_children_of_saint' => $this->number_of_children_of_saint,
                    'number_of_children_of_saint_trans' => $this->number_of_children_of_saint_trans,
                    'trans_men_and_women' => $this->trans_men_and_women,
                    'name_gender' => $this->name_gender,
                    'fully_welcomes' => $this->fully_welcomes,
                    'respect_for_trans_people' => $this->respect_for_trans_people,
                    'suffered_aggregation' => $this->suffered_aggregation,
                    'inclusion_of_the_name_of_the_land' => $this->inclusion_of_the_name_of_the_land,
                    'suggestion_id' => $this->suggestion_id,
                    'suggestion_text' => $this->suggestion_text,
                ]);
            DB::commit();

            sleep(3);

            toastr()
                ->timeOut(2000)
                ->success(__('Terreiro successfully registered!'));

            $this->redirectRoute('site.terreiros.search');
        } catch (Exception|Throwable $e) {
            DB::rollBack();
            Utils::webhook('error', $e, 'Error when creating terreiro', null);
            Log::error($e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            toastr()
                ->timeOut(2000)
                ->error(__('Error when creating terreiro!'));
        }
    }

    public function render()
    {
        return view('livewire.site.pages.terreiros.create');
    }
}
