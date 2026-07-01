<div class="flex min-h-[calc(100svh-4rem)] items-center justify-center px-6 py-12">
    <div class="w-full max-w-md rounded-3xl bg-white p-8 shadow-2xl sm:p-10">
        <h2 class="text-center text-2xl font-bold text-slate-800">{{ __('two_factor.challenge_title') }}</h2>
        <p class="mt-2 text-center text-sm text-slate-500">
            {{ $useRecovery ? __('two_factor.challenge_recovery_hint') : __('two_factor.challenge_hint') }}
        </p>

        <form wire:submit="verify" class="mt-6 space-y-4">
            @if (! $useRecovery)
                <div>
                    <label for="code" class="block text-sm font-medium text-slate-700">{{ __('two_factor.code') }}</label>
                    <input type="text" id="code" wire:model="code" inputmode="numeric" autocomplete="one-time-code"
                           autofocus maxlength="6"
                           class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-center text-lg tracking-[0.4em] focus:border-violet-500 focus:ring-violet-500">
                    @error('code') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            @else
                <div>
                    <label for="recovery_code" class="block text-sm font-medium text-slate-700">{{ __('two_factor.recovery_code') }}</label>
                    <input type="text" id="recovery_code" wire:model="recovery_code" autocomplete="off"
                           class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
                    @error('recovery_code') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            @endif

            <button type="submit" wire:loading.attr="disabled" wire:target="verify"
                    class="w-full rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:brightness-110 disabled:opacity-60">
                <span wire:loading.remove wire:target="verify">{{ __('two_factor.verify') }}</span>
                <span wire:loading wire:target="verify">{{ __('two_factor.verifying') }}</span>
            </button>
        </form>

        <button type="button" wire:click="toggleRecovery"
                class="mt-5 block w-full text-center text-sm text-violet-600 hover:underline">
            {{ $useRecovery ? __('two_factor.use_code') : __('two_factor.use_recovery') }}
        </button>
    </div>
</div>
