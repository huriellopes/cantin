<div>
    @php $f = 'block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-violet-500 focus:ring-violet-500'; @endphp
    <form wire:submit.prevent="store" class="space-y-4">
        @csrf
        <div>
            <input type="text" wire:model="name" placeholder="{{ __('page_register.name_placeholder') }}" class="{{ $f }} @error('name') border-rose-400 @enderror">
            @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <input type="email" wire:model="email" placeholder="{{ __('page_register.email_placeholder') }}" class="{{ $f }} @error('email') border-rose-400 @enderror">
            @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <input type="password" wire:model="password" placeholder="{{ __('page_register.password_placeholder') }}" class="{{ $f }} @error('password') border-rose-400 @enderror">
            @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit" wire:loading.attr="disabled" wire:target="store"
                class="w-full rounded-lg bg-gradient-to-r from-violet-600 to-pink-500 py-3 font-semibold text-white shadow-md transition hover:brightness-110 disabled:opacity-60">
            <span wire:loading.remove wire:target="store">{{ __('page_register.register') }}</span>
            <span wire:loading wire:target="store">{{ __('Loading...') }}</span>
        </button>
    </form>
</div>
