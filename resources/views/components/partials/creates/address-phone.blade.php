<fieldset class="form-group border p-3">
    <legend class="float-none w-auto px-1">Informações pessoais</legend>
    <div class="row">
        <div class="col-md-12 col-12">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" id="name" class="form-control @error('name') border-danger @enderror" wire:model.live="name" />
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-12">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" name="email" id="email" class="form-control @error('email') border-danger @enderror" wire:model.live="email" />
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 col-12">
            <label for="phone" class="form-label">{{ __('Phone') }}</label>
            <input type="text" name="phone" x-mask="(99) 9 9999-9999" id="phone" class="form-control @error('phone') border-danger @enderror" wire:model.live="phone" />
            @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</fieldset>

<fieldset class="form-group border p-3 mt-3 mb-2">
    <legend class="float-none w-auto px-1">Endereço</legend>
    <div class="row">
        <div class="col-md-6 col-12">
            <label for="zipcode" class="form-label">{{ __('Zip Code') }}</label>
            <div class="input-group">
                <input type="text" name="zipcode" id="zipcode" class="form-control @error('zipcode') border-danger @enderror" maxlength="9" wire:model.live="zipcode" x-mask="99999-999" />
                <button type="button" class="btn btn-outline-secondary zipcode-search" id="button-addon2" wire:click="searchZipCode" wire:target="searchZipCode" wire:loading.attr="disabled">
                    <i class="fa-solid fa-magnifying-glass" id="zipcode-search"></i>
                    <span wire:loading wire:target="searchZipCode" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">{{ __('Loading...') }}</span>
                    </span>
                </button>
            </div>
            @error('zipcode') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 col-12">
            <label for="street" class="form-label">{{ __('Address') }}</label>
            <input type="text" name="street" id="street" class="form-control @error('street') border-danger @enderror" wire:model.live="street" value="{{ $street }}" />
            @error('street') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-12">
            <label for="complement" class="form-label">{{ __('Complement') }}</label>
            <input type="text" name="complement" id="complement" class="form-control @error('complement') border-danger @enderror" wire:model.live="complement" value="{{ $complement }}" />
            @error('complement') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 col-12">
            <label for="neighborhood" class="form-label">{{ __('Neighborhood') }}</label>
            <input type="text" name="neighborhood" id="neighborhood" class="form-control @error('neighborhood') border-danger @enderror" wire:model.live="neighborhood" value="{{ $neighborhood }}" />
            @error('neighborhood') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-12">
            <label for="state_id" class="form-label">{{ __('State') }}</label>
            <select name="state_id" id="state_id" class="form-control @error('state_id') border-danger @enderror" wire:model.live="state_id">
                <option selected value="">{{ __('Select the state') }}</option>
                @foreach ($states as $state)
                    <option value="{{ $state->id }}" {{ $state_id === $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                @endforeach
            </select>
            @error('state_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 col-12">
            <label for="city_id" class="form-label">{{ __('City') }}</label>
            <select name="city_id" id="city_id" class="form-control @error('city_id') border-danger @enderror" wire:model.live="city_id" wire:loading.attr="disabled" wire:target="state_id">
                <option selected>{{ __('Select the city') }}</option>
                @if (!empty($cities))
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}" {{ $city_id === $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                    @endforeach
                @endif
            </select>
            @error('city_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</fieldset>
