<div class="flex min-h-[calc(100svh-4rem)] items-center justify-center px-6 py-12">
    <div class="grid w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl md:grid-cols-2">
        {{-- Imagem --}}
        <div class="relative hidden md:block">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('{{ $image }}')"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-violet-700/70 to-pink-600/60"></div>
            <div class="relative flex h-full flex-col justify-end p-8 text-white">
                <span class="text-2xl font-extrabold">Ca<span class="text-pink-200">NTI</span>n</span>
                <p class="mt-2 text-sm text-white/90">{{ __('login.tagline') }}</p>
            </div>
        </div>

        {{-- Formulário --}}
        <div class="p-8 sm:p-10">
            <h2 class="text-center text-2xl font-bold text-slate-800">{{ __('login.title') }}</h2>

            @error('message')
                <div class="mt-4 rounded-lg bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $message }}</div>
            @enderror

            <form method="POST" action="{{ route('site.auth.login.post') }}" class="mt-6 space-y-4" x-data="{ submitting: false }" @submit="submitting = true">
                @csrf
                <div>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('login.email') }}" required autofocus
                           class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-violet-500 focus:ring-violet-500 @error('email') border-rose-400 @enderror">
                    @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <input type="password" name="password" placeholder="{{ __('login.password') }}" required
                           class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-violet-500 focus:ring-violet-500 @error('password') border-rose-400 @enderror">
                    @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                               class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                        {{ __('login.remember') }}
                    </label>
                    <a href="{{ route('site.home') }}" wire:navigate class="text-sm text-slate-400 hover:text-violet-600">{{ __('login.back_to_site') }}</a>
                </div>

                <button type="submit" x-bind:disabled="submitting"
                        class="w-full rounded-lg bg-gradient-to-r from-violet-600 to-pink-500 py-3 font-semibold text-white shadow-md transition hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-70">
                    <span x-show="! submitting">{{ __('login.submit') }}</span>
                    <span x-show="submitting" x-cloak>{{ __('common.processing') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>
