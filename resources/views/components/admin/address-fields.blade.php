@props(['states', 'cities'])

<section class="space-y-4">
    <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Endereço</h4>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="flex items-end gap-2">
            <div class="flex-1"><x-admin.input label="CEP" name="form.zipcode" wire:model="form.zipcode" x-mask="99999-999" /></div>
            <button type="button" wire:click="buscarCep" class="mb-0.5 rounded-lg bg-slate-100 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-200">Buscar</button>
        </div>
        <x-admin.input label="Endereço" name="form.address" wire:model="form.address" />
        <x-admin.input label="Complemento" name="form.complement" wire:model="form.complement" />
        <x-admin.input label="Bairro" name="form.neighborhood" wire:model="form.neighborhood" />
        <div class="space-y-1">
            <label class="block text-sm font-medium text-slate-700">Estado</label>
            <select wire:model.live="form.state_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Selecione...</option>
                @foreach ($states as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
            </select>
            @error('form.state_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-slate-700">Cidade</label>
            <select wire:model="form.city_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Selecione...</option>
                @foreach ($cities as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
            </select>
            @error('form.city_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>
</section>
