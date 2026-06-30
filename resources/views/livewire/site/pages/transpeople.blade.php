<div class="mx-auto max-w-3xl px-6 py-16">
    <header class="text-center">
        <h1 class="text-3xl font-extrabold text-slate-800 sm:text-4xl">{{ __('Terreiro Trans People Registry') }}</h1>
        <p class="mt-2 text-slate-500">Cadastre-se para fazer parte da nossa rede de acolhimento.</p>
    </header>

    <form wire:submit.prevent="store" class="mt-10 space-y-5">
        @include('components.partials.creates.address-phone')

        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled" wire:target="store"
                    class="rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-7 py-3 font-semibold text-white shadow-md transition hover:brightness-110 disabled:opacity-60">
                <span wire:loading.remove wire:target="store">{{ __('Register') }}</span>
                <span wire:loading wire:target="store">{{ __('Loading...') }}</span>
            </button>
        </div>
    </form>
</div>
