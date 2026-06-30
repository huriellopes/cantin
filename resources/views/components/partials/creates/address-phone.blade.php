@php
    $field = 'block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500';
    $err = 'mt-1 text-xs text-rose-600';
@endphp

<fieldset class="rounded-2xl border border-slate-200 p-5">
    <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-500">Informações pessoais</legend>
    <div class="grid grid-cols-1 gap-4">
        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-slate-700">{{ __('Name') }}</label>
            <input type="text" id="name" wire:model.live="name" class="{{ $field }}" />
            @error('name') <p class="{{ $err }}">{{ $message }}</p> @enderror
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-slate-700">{{ __('Email') }}</label>
                <input type="email" id="email" wire:model.live="email" class="{{ $field }}" />
                @error('email') <p class="{{ $err }}">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="phone" class="mb-1 block text-sm font-medium text-slate-700">{{ __('Phone') }}</label>
                <input type="text" id="phone" x-mask="(99) 9 9999-9999" wire:model.live="phone" class="{{ $field }}" />
                @error('phone') <p class="{{ $err }}">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</fieldset>

<fieldset class="mt-5 rounded-2xl border border-slate-200 p-5">
    <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-500">Endereço</legend>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="zipcode" class="mb-1 block text-sm font-medium text-slate-700">{{ __('Zip Code') }}</label>
            <div class="flex gap-2">
                <input type="text" id="zipcode" maxlength="9" wire:model.live="zipcode" x-mask="99999-999" class="{{ $field }}" />
                <button type="button" wire:click="searchZipCode" wire:loading.attr="disabled" wire:target="searchZipCode"
                        class="shrink-0 rounded-lg bg-slate-100 px-3 text-sm font-medium text-slate-600 hover:bg-slate-200">
                    <span wire:loading.remove wire:target="searchZipCode">Buscar</span>
                    <span wire:loading wire:target="searchZipCode">...</span>
                </button>
            </div>
            @error('zipcode') <p class="{{ $err }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="street" class="mb-1 block text-sm font-medium text-slate-700">{{ __('Address') }}</label>
            <input type="text" id="street" wire:model.live="street" class="{{ $field }}" />
            @error('street') <p class="{{ $err }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="complement" class="mb-1 block text-sm font-medium text-slate-700">{{ __('Complement') }}</label>
            <input type="text" id="complement" wire:model.live="complement" class="{{ $field }}" />
            @error('complement') <p class="{{ $err }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="neighborhood" class="mb-1 block text-sm font-medium text-slate-700">{{ __('Neighborhood') }}</label>
            <input type="text" id="neighborhood" wire:model.live="neighborhood" class="{{ $field }}" />
            @error('neighborhood') <p class="{{ $err }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="state_id" class="mb-1 block text-sm font-medium text-slate-700">{{ __('State') }}</label>
            <select id="state_id" wire:model.live="state_id" class="{{ $field }}">
                <option value="">{{ __('Select the state') }}</option>
                @foreach ($states as $state)
                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                @endforeach
            </select>
            @error('state_id') <p class="{{ $err }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="city_id" class="mb-1 block text-sm font-medium text-slate-700">{{ __('City') }}</label>
            <select id="city_id" wire:model.live="city_id" wire:loading.attr="disabled" wire:target="state_id" class="{{ $field }}">
                <option value="">{{ __('Select the city') }}</option>
                @if (! empty($cities))
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                @endif
            </select>
            @error('city_id') <p class="{{ $err }}">{{ $message }}</p> @enderror
        </div>
    </div>
</fieldset>
