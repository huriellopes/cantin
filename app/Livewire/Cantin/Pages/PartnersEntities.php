<?php

namespace App\Livewire\Cantin\Pages;

use App\Actions\Address\FillAddressAction;
use App\Models\Address;
use App\Models\City;
use App\Models\ParternEntity;
use App\Models\State;
use App\Traits\Utils;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Exception;
use Throwable;

class PartnersEntities extends Component
{
    use Utils;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $zipcode = '';
    public string $address = '';
    public int $number = 0;
    public string $complement = '';
    public string $neighborhood = '';
    public ?int $state_id = null;
    public ?int $city_id = null;
    public string $activity_carried_out = '';

    public $states;
    public $cities;

    public bool $loading = false;

    /**
     * @return void
     */
    public function mount(): void
    {
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
    public function updated($property): void
    {
        if ($property !== 'state_id') {
            $this->validateOnly($property);
        }
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

    /**
     * @return void
     * @throws Exception
     */
    public function searchZipCode(): void
    {
        $this->loading = true;
        try {
            if (empty($this->zipcode)) {
                toastr()
                    ->timeOut(2000)
                    ->error(__('Invalid zipcode!'));
                $this->loading = false;
                return;
            }
            $cleanedZipCode = $this->clearMask($this->zipcode);
            if (!preg_match('/^\d{8}$/', $cleanedZipCode)) {
                toastr()
                    ->timeOut(2000)
                    ->error(__('Invalid zipcode!'));
                return;
            }

            $address = FillAddressAction::exec($this->zipcode);

            $stateModel = State::query()
                ->where('name', '=', $address->state)
                ->first();

            $cityModel = City::query()
                ->where('name', '=', $address->city)
                ->where('state_id', '=', $stateModel->id ?? null)
                ->first();

            $this->address = $address->address;
            $this->complement = $address->complement;
            $this->neighborhood = $address->neighborhood;
            if ($stateModel) {
                $this->state_id = $stateModel->id;
            } else {
                $this->state_id = null;
                $this->cities = collect();
            }

            if ($cityModel && $this->state_id === $cityModel->state_id) {
                $this->city_id = $cityModel->id;
            } else {
                $this->city_id = null;
            }
        } catch (Exception|Throwable $e) {
            $this->webhook('error', $e, 'Cep not found', null);

            Log::error($e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            toastr()
                ->timeOut(2000)
                ->error(__('Error when searching for zip code!'));
        } finally {
            $this->loading = false;
        }
    }

    /**
     * @return void
     */
    public function store(): void
    {
        $this->validate();

        $address = Address::query()
            ->where('zipcode', '=', $this->clearMask($this->zipcode))
            ->first();

        if (!$address) {
            $address = Address::create([
                'zipcode' => $this->clearMask($this->zipcode),
                'address' => $this->address,
                'complement' => $this->complement,
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
