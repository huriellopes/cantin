<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages;

use App\Actions\Address\FillAddressAction;
use App\Models\Address;
use App\Models\City;
use App\Models\State;
use App\Models\TransPeople as Trans;
use App\Traits\Utils;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class Transpeople extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $zipcode = '';

    public string $street = '';

    public string $complement = '';

    public string $neighborhood = '';

    public ?int $state_id = null;

    public $latitude;

    public $longitude;

    public $states;

    public ?int $city_id = null;

    public $cities;

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

    public function updatedStateId(?int $value): void
    {
        $this->validateOnly('state_id');

        $this->city_id = null;
        $this->cities = null;

        if ($value) {
            $this->loadCities($value);
        }
    }

    public function updated($property): void
    {
        if ($property !== 'state_id') {
            $this->validateOnly($property);
        }
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
                'file' => $e->getFile(),
            ]);

            toastr()
                ->timeOut(2000)
                ->error(__('Error when searching for zip code!'));
        }
    }

    public function store(): void
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
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude,
                ]);
            }

            $transExist = Trans::query()->where('email', '=', $this->email)->first();

            if ($transExist) {
                toastr()
                    ->timeOut(2000)
                    ->error(__('Trans people already registered!'));

                return;
            }

            Trans::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => Utils::clearMask($this->phone),
                'address_id' => $address->id,
            ]);

            $this->reset([
                'name',
                'email',
                'phone',
                'zipcode',
                'street',
                'number',
                'complement',
                'neighborhood',
                'state_id',
                'city_id',
                'latitude',
                'longitude',
            ]);
            DB::commit();

            toastr()
                ->timeOut(2000)
                ->success(__('Trans people successfully registered!'));
        } catch (Exception|Throwable $e) {
            DB::rollBack();
            Utils::webhook('error', $e, 'Error when registering trans people', null);
            Log::error($e->getMessage());
            toastr()
                ->timeOut(2000)
                ->error(__('Error when registering trans people!'));
        }
    }

    public function render()
    {
        return view('livewire.site.pages.transpeople');
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
     * @return string[]
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|string',
            'phone' => 'required|string',
            'zipcode' => 'required|string',
            'street' => 'required|string',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
        ];
    }

    protected function messages(): array
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
            'street.required' => __('The address field is required.'),
            'street.string' => __('The address field only allows characters.'),
            'complement.string' => __('The complement field only allows characters.'),
            'neighborhood.required' => __('The neighborhood field is required.'),
            'neighborhood.string' => __('The neighborhood field only allows characters.'),
            'state_id.required' => __('The state field is required.'),
            'state_id.integer' => __('The state field is only allowed numeric characters.'),
            'city_id.required' => __('The city field is required.'),
            'city_id.integer' => __('The city field is only allowed numeric characters.'),
        ];
    }
}
