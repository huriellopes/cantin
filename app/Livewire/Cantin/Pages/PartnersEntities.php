<?php

namespace App\Livewire\Cantin\Pages;

use App\Models\Address;
use App\Models\City;
use App\Models\ParternEntity;
use App\Models\State;
use App\Traits\Utils;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Exception;
use Throwable;

class PartnersEntities extends Component
{
    use Utils;

    public string $name;
    public string $email;
    public string $phone;
    public string $zipcode;
    public string $address;
    public int $number;
    public string $complement;
    public string $neighborhood;
    public int $state_id;
    public int $city_id;
    public string $activity_carried_out;

    public $states;
    public $cities;

    public bool $loading = false;

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->states = Cache::remember('states_partners', 60 * 60 * 24, function () {
            return State::query()
                ->select('id', 'name')
                ->limit(1000)
                ->get();
        });

        $this->cities = Cache::remember('cities_partners', 60 * 60 * 24, function () {
            return City::query()->select('id', 'name')->limit(10000)->get();
        });
    }

    /**
     * @return string[]
     */
    protected function rules() : array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|string',
            'phone' => 'required|string',
            'zipcode' => 'required|string',
            'address' => 'required|string',
            'number' => 'required|numeric',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'activity_carried_out' => 'required|string'
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
            'email.required' => __('The email field is required.'),
            'email.string' => __('The email field only allows characters.'),
            'email.email' => __('The email field is invalid.'),
            'phone.required' => __('The phone field is required.'),
            'phone.string' => __('The phone field only allows characters.'),
            'zipcode.required' => __('The zipcode field is required.'),
            'zipcode.string' => __('The zipcode field only allows characters.'),
            'address.required' => __('The address field is required.'),
            'address.string' => __('The address field only allows characters.'),
            'number.required' => __('The number field is required.'),
            'number.numeric' => __('The number field is only allowed numeric characters.'),
            'complement.string' => __('The complement field only allows characters.'),
            'neighborhood.required' => __('The neighborhood field is required.'),
            'neighborhood.string' => __('The neighborhood field only allows characters.'),
            'state_id.required' => __('The state field is required.'),
            'state_id.integer' => __('The state field is only allowed numeric characters.'),
            'city_id.required' => __('The city field is required.'),
            'city_id.integer' => __('The city field is only allowed numeric characters.'),
            'activity_carried_out' => __('The activity carried out field is required.'),
        ];
    }

    /**
     * @param $property
     * @return void
     */
    public function updated($property): void
    {
        $this->validateOnly($property);
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
    public function store(): void
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

        ParternEntity::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->clearMask($this->phone),
            'address_id' => $address->id,
            'activity_carried_out' => $this->activity_carried_out,
        ]);

        $this->reset([
            'name',
            'email',
            'phone',
            'zipcode',
            'address',
            'number',
            'complement',
            'neighborhood',
            'state_id',
            'city_id',
            'activity_carried_out'
        ]);

        sleep(3);

        toastr()
            ->timeOut(2000)
            ->success(__('Partner entity successfully registered!'));
    }

    public function render()
    {
        return view('livewire.cantin.pages.partners-entities');
    }
}
