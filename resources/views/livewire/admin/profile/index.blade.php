<div class="mx-auto max-w-3xl space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">{{ __('admin.profile.title') }}</h1>
        <p class="mt-1 text-sm text-slate-500">{{ __('admin.profile.subtitle') }}</p>
    </div>

    {{-- Dados pessoais --}}
    <form wire:submit="updateProfile" class="space-y-5 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">{{ __('admin.profile.personal_data') }}</h2>
        <x-admin.input label="{{ __('admin.profile.name') }}" name="form.name" wire:model="form.name" />
        <x-admin.input label="{{ __('admin.profile.email') }}" name="form.email" type="email" wire:model="form.email" />
        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled" wire:target="updateProfile"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-700 disabled:cursor-not-allowed disabled:opacity-70">
                @svg('lucide-save', 'h-4 w-4')
                <span wire:loading.remove wire:target="updateProfile">{{ __('admin.profile.save') }}</span>
                <span wire:loading wire:target="updateProfile">{{ __('common.saving') }}</span>
            </button>
        </div>
    </form>

    {{-- Alterar senha --}}
    <form wire:submit="updatePassword" class="space-y-5 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">{{ __('admin.profile.change_password') }}</h2>
        <x-admin.input label="{{ __('admin.profile.current_password') }}" name="form.current_password" type="password" wire:model="form.current_password" autocomplete="current-password" />
        <x-admin.input label="{{ __('admin.profile.new_password') }}" name="form.password" type="password" wire:model="form.password" autocomplete="new-password" />
        <x-admin.input label="{{ __('admin.profile.confirm_password') }}" name="form.password_confirmation" type="password" wire:model="form.password_confirmation" autocomplete="new-password" />
        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-700 disabled:cursor-not-allowed disabled:opacity-70">
                @svg('lucide-key-round', 'h-4 w-4')
                <span wire:loading.remove wire:target="updatePassword">{{ __('admin.profile.update_password') }}</span>
                <span wire:loading wire:target="updatePassword">{{ __('common.saving') }}</span>
            </button>
        </div>
    </form>

    {{-- Autenticação em dois fatores (2FA) --}}
    @php $twoFaOn = auth()->user()->hasTwoFactorEnabled(); @endphp
    <div class="space-y-5 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">{{ __('two_factor.title') }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ __('two_factor.subtitle') }}</p>
            </div>
            <span class="shrink-0">
                <x-admin.badge :color="$twoFaOn ? 'emerald' : 'slate'">
                    {{ $twoFaOn ? __('two_factor.status_on') : __('two_factor.status_off') }}
                </x-admin.badge>
            </span>
        </div>

        {{-- Códigos de recuperação (exibidos uma vez após ativar) --}}
        @if (! empty($recoveryCodes))
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                <p class="text-sm font-medium text-amber-800">{{ __('two_factor.recovery_title') }}</p>
                <p class="mt-1 text-xs text-amber-700">{{ __('two_factor.recovery_desc') }}</p>
                <div class="mt-3 grid grid-cols-2 gap-2 font-mono text-sm text-slate-700">
                    @foreach ($recoveryCodes as $rc)
                        <span class="rounded bg-white px-2 py-1 text-center">{{ $rc }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($showTwoFactorSetup)
            {{-- Passo de configuração: QR + confirmação --}}
            <div class="grid gap-5 sm:grid-cols-[auto,1fr] sm:items-center">
                <div class="mx-auto rounded-lg border border-slate-200 p-3">{!! $qrCode !!}</div>
                <div>
                    <p class="text-sm text-slate-600">{{ __('two_factor.scan_hint') }}</p>
                    <label for="twoFactorCode" class="mt-3 block text-sm font-medium text-slate-700">{{ __('two_factor.code') }}</label>
                    <input type="text" id="twoFactorCode" wire:model="twoFactorCode" inputmode="numeric" maxlength="6"
                           class="mt-1 block w-40 rounded-lg border border-slate-300 px-3 py-2 text-center text-lg tracking-[0.3em] focus:border-violet-500 focus:ring-violet-500">
                    @error('twoFactorCode') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    <div class="mt-4 flex gap-2">
                        <button type="button" wire:click="confirmTwoFactor" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">{{ __('two_factor.confirm') }}</button>
                        <button type="button" wire:click="disableTwoFactor" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">{{ __('two_factor.cancel') }}</button>
                    </div>
                </div>
            </div>
        @elseif ($twoFaOn)
            <button type="button" wire:click="disableTwoFactor"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                @svg('lucide-shield-off', 'h-4 w-4') {{ __('two_factor.disable') }}
            </button>
        @else
            <button type="button" wire:click="enableTwoFactor"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-700">
                @svg('lucide-shield-check', 'h-4 w-4') {{ __('two_factor.enable') }}
            </button>
        @endif
    </div>

    {{-- Zona de risco --}}
    <div class="space-y-4 rounded-2xl border border-rose-200 bg-rose-50/50 p-6">
        <div>
            <h2 class="text-lg font-semibold text-rose-700">{{ __('admin.profile.delete_zone') }}</h2>
            <p class="mt-1 text-sm text-rose-600/80">{{ __('admin.profile.delete_desc') }}</p>
        </div>
        <button type="button" wire:click="confirmDeleteAccount" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
            @svg('lucide-trash-2', 'h-4 w-4') {{ __('admin.profile.delete_button') }}
        </button>
    </div>

    {{-- Modal de confirmação por senha --}}
    <x-admin.modal show="showDelete" title="{{ __('admin.profile.delete_modal_title') }}">
        <form wire:submit="deleteAccount" class="space-y-4">
            <p class="text-sm text-slate-600">{{ __('admin.profile.delete_modal_desc') }}</p>
            <x-admin.input label="{{ __('admin.profile.your_password') }}" name="form.delete_password" type="password" wire:model="form.delete_password" autocomplete="current-password" />
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="$wire.showDelete = false" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">{{ __('admin.profile.cancel') }}</button>
                <button type="submit" wire:loading.attr="disabled" wire:target="deleteAccount"
                        class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700 disabled:cursor-not-allowed disabled:opacity-70">
                    @svg('lucide-trash-2', 'h-4 w-4')
                    <span wire:loading.remove wire:target="deleteAccount">{{ __('admin.profile.delete_confirm') }}</span>
                    <span wire:loading wire:target="deleteAccount">{{ __('common.deleting') }}</span>
                </button>
            </div>
        </form>
    </x-admin.modal>
</div>
