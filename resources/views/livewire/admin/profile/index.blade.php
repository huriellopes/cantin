<div class="mx-auto max-w-3xl space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">{{ __('admin.profile.title') }}</h1>
        <p class="mt-1 text-sm text-slate-500">{{ __('admin.profile.subtitle') }}</p>
    </div>

    {{-- Dados pessoais --}}
    <form wire:submit="updateProfile" class="space-y-5 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">{{ __('admin.profile.personal_data') }}</h2>
        <x-admin.input label="{{ __('admin.profile.name') }}" name="name" wire:model="name" />
        <x-admin.input label="{{ __('admin.profile.email') }}" name="email" type="email" wire:model="email" />
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
        <x-admin.input label="{{ __('admin.profile.current_password') }}" name="current_password" type="password" wire:model="current_password" autocomplete="current-password" />
        <x-admin.input label="{{ __('admin.profile.new_password') }}" name="password" type="password" wire:model="password" autocomplete="new-password" />
        <x-admin.input label="{{ __('admin.profile.confirm_password') }}" name="password_confirmation" type="password" wire:model="password_confirmation" autocomplete="new-password" />
        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-700 disabled:cursor-not-allowed disabled:opacity-70">
                @svg('lucide-key-round', 'h-4 w-4')
                <span wire:loading.remove wire:target="updatePassword">{{ __('admin.profile.update_password') }}</span>
                <span wire:loading wire:target="updatePassword">{{ __('common.saving') }}</span>
            </button>
        </div>
    </form>

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
            <x-admin.input label="{{ __('admin.profile.your_password') }}" name="delete_password" type="password" wire:model="delete_password" autocomplete="current-password" />
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
