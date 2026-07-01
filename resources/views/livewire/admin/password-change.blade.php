<div class="mx-auto max-w-md py-10">
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <h2 class="text-xl font-bold text-slate-800">{{ __('msg_password_change.title') }}</h2>
        <p class="mt-1 text-sm text-slate-500">{{ __('msg_password_change.subtitle') }}</p>

        <form wire:submit="save" class="mt-6 space-y-4">
            <div class="space-y-1">
                <label for="password" class="block text-sm font-medium text-slate-700">{{ __('msg_password_change.new_password') }}</label>
                <input type="password" id="password" wire:model="form.password" autocomplete="new-password"
                       class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
                @error('form.password') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">{{ __('msg_password_change.confirm_password') }}</label>
                <input type="password" id="password_confirmation" wire:model="form.password_confirmation" autocomplete="new-password"
                       class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
            </div>

            <button type="submit" class="w-full rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                <span wire:loading.remove wire:target="save">{{ __('msg_password_change.submit') }}</span>
                <span wire:loading wire:target="save">{{ __('common.saving') }}</span>
            </button>
        </form>
    </div>
</div>
