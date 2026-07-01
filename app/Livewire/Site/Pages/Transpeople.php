<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages;

use App\Actions\Address\FillAddressAction;
use App\Livewire\Forms\TranspeopleForm;
use App\Models\Address;
use App\Models\City;
use App\Models\State;
use App\Models\TransPeople as Trans;
use App\Traits\Utils;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

class Transpeople extends Component
{
    public TranspeopleForm $form;

    public $states;

    public $cities;

    public function mount(): void
    {
        $this->states = Cache::remember('all_brazilian_states', 60 * 60 * 24, fn () => State::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get());

        $this->cities = collect();

        if ($this->form->state_id) {
            $this->loadCities($this->form->state_id);
        }
    }

    public function updated(string $property, $value): void
    {
        if (!str_starts_with($property, 'form.')) {
            return;
        }

        $field = mb_substr($property, mb_strlen('form.'));

        // Cascata estado -> cidade: ao trocar o estado, limpa a cidade e recarrega a lista.
        if ($field === 'state_id') {
            $this->form->validateOnly('state_id');

            $this->form->city_id = null;
            $this->cities = collect();

            if ($value) {
                $this->loadCities((int) $value);
            }

            return;
        }

        $this->form->validateOnly($field);
    }

    public function searchZipCode(): void
    {
        $cleanedZipCode = preg_replace('/\D/', '', $this->form->zipcode);

        if (!preg_match('/^\d{8}$/', (string) $cleanedZipCode)) {
            $this->form->addError('zipcode', __('Invalid zipcode!'));

            return;
        }

        // Mesma lógica robusta do dashboard (InteractsWithAddress::buscarCep).
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

            $transExist = Trans::query()->where('email', '=', $this->form->email)->first();

            if ($transExist) {
                toastr()
                    ->timeOut(2000)
                    ->error(__('Trans people already registered!'));

                return;
            }

            Trans::query()->create([
                'name' => $this->form->name,
                'email' => $this->form->email,
                'phone' => Utils::clearMask($this->form->phone),
                'address_id' => $address->id,
            ]);

            $this->form->reset();
            DB::commit();

            toastr()
                ->timeOut(2000)
                ->success(__('Trans people successfully registered!'));
        } catch (Exception|Throwable $e) {
            DB::rollBack();
            Utils::webhook('error', $e, 'Error when registering trans people');
            Log::error($e->getMessage());
            toastr()
                ->timeOut(2000)
                ->error(__('Error when registering trans people!'));
        }
    }

    public function render(): Factory|View
    {
        return view('livewire.site.pages.transpeople');
    }

    protected function loadCities(int $stateId): void
    {
        $this->cities = City::query()
            ->select('id', 'name')
            ->where('state_id', '=', $stateId)
            ->orderBy('name')
            ->get();
    }
}
