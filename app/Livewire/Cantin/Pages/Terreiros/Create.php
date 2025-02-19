<?php

namespace App\Livewire\Cantin\Pages\Terreiros;

use App\Models\Address;
use App\Models\City;
use App\Models\NationsTerreiro;
use App\Models\State;
use App\Models\Suggestion;
use App\Models\Terreiro;
use App\Models\TerreiroQuestion;
use App\Models\TypePeople;
use App\Traits\Utils;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Exception;
use Throwable;

class Create extends Component
{
    use Utils;

    public int $currentStep = 1;

    public $name;
    public $nation_terreiro_id;
    public $phone;
    public $fundationed_at;
    public $leadership_orunko;
    public $color_of_leadership;
    public $zipcode;
    public $address;
    public $complement;
    public $number;
    public $state_id;
    public $city_id;
    public $neighborhood;

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

    public bool $loading = false;

    public function updatedSuggestionID($option): void
    {
        $this->showField = (int) $option === 1 ? (int) $option === 2 : (int) $option === 3;
        $this->suggestion_text = '';
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
            'fundationed_at' => 'required|date',
            'leadership_orunko' => 'required|string',
            'color_of_leadership' => 'required|string',
            'zipcode' => 'required|string',
            'address' => 'required|string',
            'number' => 'required|numeric',
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
            'fundationed_at.required' => __('The fundationed_at field is required.'),
            'fundationed_at.date' => __('The fundationed_at field is invalid.'),
            'leadership_orunko.required' => __('The leadership_orunko field is required.'),
            'leadership_orunko.string' => __('The leadership_orunko field only allows characters.'),
            'color_of_leadership.required' => __('The color_of_leadership field is required.'),
            'color_of_leadership.string' => __('The color_of_leadership field only allows characters.'),
            'zipcode.required' => __('The zipcode field is required.'),
            'zipcode.string' => __('The zipcode field only allows characters.'),
            'address.required' => __('The address field is required.'),
            'address.string' => __('The address field only allows characters.'),
            'number.required' => __('The number field is required.'),
            'number.number' => __('The number field is only allowed numeric characters.'),
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

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->nations = NationsTerreiro::query()
            ->select('id', 'name')
            ->get();

        $this->states = Cache::remember('states_terreiro', 60 * 60 * 24, function () {
            return State::query()
                ->select('id', 'name')
                ->limit(1000)
                ->get();
        });

        $this->cities = Cache::remember('cities_terreiro', 60 * 60 * 24, function () {
            return City::query()->select('id', 'name')->limit(10000)->get();
        });

        $this->typePeoples = TypePeople::query()
            ->select('id', 'name')
            ->get();

        $this->suggestions = Suggestion::query()
            ->select('id', 'name')
            ->get();
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

    /**
     * @return void
     */
    public function searchZipCode(): void
    {
        $this->loading = true;

        try {
            if (empty($this->zipcode)) {
                toastr()
                    ->timeOut(2000)
                    ->error(__('Invalid zipcode!'));
                return;
            }

            if (!preg_match('/^\d{8}$/', $this->clearMask($this->zipcode))) {
                toastr()
                    ->timeOut(2000)
                    ->error(__('Invalid zipcode!'));
                return;
            }

            $response = Http::timeout(3000)->withHeaders([
                'Content-Type' => 'application/json'
            ])->get(config('services.viacep.endpoint').$this->clearMask($this->zipcode).'/json');

            // Verifique se houve erro na requisição
            if ($response->failed()) {
                toastr()
                    ->timeOut(2000)
                    ->error(__('Error when searching for zip code!'));
                return;
            }

            // Verifique se o CEP foi encontrado
            if ($response->json('erro')) {
                toastr()
                    ->timeOut(2000)
                    ->error(__('Zip code not found!'));
                return;
            }

            // Defina o endereço encontrado
            $data = $response->json();

            $data['state_id'] = State::query()->select('id', 'name')->where('abbr', $data['uf'])->first();
            $data['city_id'] = City::query()->select('id', 'name')->where('name', $data['localidade'])->first();

            $this->address = $data['logradouro'];
            $this->complement = $data['complemento'];
            $this->neighborhood = $data['bairro'];
            $this->state_id = $data['state_id']->id;
            $this->city_id = $data['city_id']->id;
        } catch (Exception|Throwable $e) {
            $this->webhook('error', $e, 'Cep not found', null);

            Log::error($e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            toastr()
                ->timeOut(2000)
                ->error(__('Error when searching for zip code!'));
        }

        $this->loading = false;
    }

    /**
     * @param int $state
     * @return void
     */
    public function searchCity(int $state): void
    {
        if (!empty($state)) {
            $this->cities = null;

            $this->cities = City::query()
                ->select('id', 'name')
                ->where('state_id', '=', $state)
                ->get();
        }
    }

    /**
     * @return void
     */
    public function store() : void
    {
        $this->validate();

        $address = Address::query()->where('zipcode', '=', $this->clearMask($this->zipcode))->first();

        if (!$address) {
            $address = Address::create([
                'zipcode' => $this->clearMask($this->zipcode),
                'address' => $this->address,
                'complement' => $this->complement,
                'number' => $this->number,
                'neighborhood' => $this->neighborhood,
                'state_id' => $this->state_id,
                'city_id' => $this->city_id,
            ]);
        }

        $terreiro = Terreiro::create([
            'name' => $this->name,
            'nation_terreiro_id' => $this->nation_terreiro_id,
            'phone' => $this->clearMask($this->phone),
            'fundationed_at' => $this->fundationed_at,
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

        $this->reset([
            'name',
            'nation_terreiro_id',
            'phone',
            'fundationed_at',
            'leadership_orunko',
            'color_of_leadership',
            'zipcode',
            'address',
            'number',
            'complement',
            'neighborhood',
            'state_id',
            'city_id',
            'type_people_id',
            'number_of_children_of_saint',
            'number_of_children_of_saint_trans',
            'trans_men_and_women',
            'name_gender',
            'fully_welcomes',
            'respect_for_trans_people',
            'suffered_aggregation',
            'inclusion_of_the_name_of_the_land',
            'suggestion_id',
            'suggestion_text',
        ]);

        toastr()
            ->timeOut(2000)
            ->success(__('Terreiro successfully registered!'));

        $this->redirectRoute('site.home');
    }

    public function render()
    {
        return view('livewire.cantin.pages.terreiros.create');
    }
}
