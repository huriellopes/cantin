<div class="mx-auto max-w-3xl px-6 py-16">
    <header class="text-center">
        <h1 class="text-3xl font-extrabold text-slate-800 sm:text-4xl">{{ __('page_partners.title') }}</h1>
        <p class="mt-2 text-slate-500">{{ __('page_partners.subtitle') }}</p>
    </header>

    <form wire:submit.prevent="store" class="mt-10 space-y-5">
        @include('components.partials.creates.address-phone')

        <div>
            <label for="activity_carried_out" class="mb-1 block text-sm font-medium text-slate-700">{{ __('page_partners.activity_carried_out') }}</label>
            <textarea id="activity_carried_out" rows="5" wire:model.live="form.activity_carried_out"
                      class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
            @error('form.activity_carried_out') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled" wire:target="store"
                    class="rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-7 py-3 font-semibold text-white shadow-md transition hover:brightness-110 disabled:opacity-60">
                <span wire:loading.remove wire:target="store">{{ __('page_partners.register') }}</span>
                <span wire:loading wire:target="store">{{ __('page_partners.loading') }}</span>
            </button>
        </div>
    </form>
</div>
