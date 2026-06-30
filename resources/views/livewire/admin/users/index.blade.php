<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ __('crud_users.title') }}</h2>
            <p class="text-sm text-slate-500">{{ __('crud_users.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" wire:click="export" wire:loading.attr="disabled" wire:target="export"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 disabled:opacity-70">
                @svg('lucide-file-spreadsheet', 'h-4 w-4')
                <span wire:loading.remove wire:target="export">{{ __('exports.export') }}</span>
                <span wire:loading wire:target="export">{{ __('common.exporting') }}</span>
            </button>
            <button wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">
                @svg('lucide-plus', 'h-4 w-4')
                {{ __('crud_users.new_user') }}
            </button>
        </div>
    </div>

    @if ($generatedPassword)
        <div class="rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-800">
            {{ __('crud_users.new_password_for') }} <strong>{{ $generatedFor }}</strong>:
            <code class="rounded bg-amber-100 px-2 py-0.5 font-mono">{{ $generatedPassword }}</code>
            <button wire:click="$set('generatedPassword', null)" class="ml-2 underline">{{ __('crud_users.hide') }}</button>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <x-admin.table-toolbar :options="$this->perPageOptions()" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <x-admin.th column="id" :sort-field="$sortField" :sort-direction="$sortDirection">#</x-admin.th>
                        <x-admin.th column="name" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_users.col_name') }}</x-admin.th>
                        <x-admin.th column="email" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_users.col_email') }}</x-admin.th>
                        <th class="px-4 py-3">{{ __('crud_users.col_role') }}</th>
                        <x-admin.th column="status" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('common.status') }}</x-admin.th>
                        <x-admin.th column="last_login_at" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_users.col_last_login') }}</x-admin.th>
                        <th class="px-4 py-3 text-right">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50" wire:key="user-{{ $user->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $user->id }}</td>
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $user->role_id?->label() }}</td>
                            <td class="px-4 py-3">
                                <x-admin.badge :color="$user->status?->getColor() ?? 'slate'">{{ $user->status?->label() }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $user->last_login_at?->format('d/m/Y H:i') ?? __('crud_users.never_logged_in') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-admin.action icon="view" color="sky" label="{{ __('common.view') }}" wire:click="view({{ $user->id }})" />
                                    <x-admin.action icon="edit" color="violet" label="{{ __('common.edit') }}" wire:click="edit({{ $user->id }})" />
                                    @if ($user->id !== auth()->id())
                                        <x-admin.action icon="toggle"
                                            :color="$user->status === \App\Enum\Status::ACTIVE ? 'amber' : 'emerald'"
                                            :label="$user->status === \App\Enum\Status::ACTIVE ? __('common.deactivate') : __('common.activate')"
                                            wire:click="confirmToggle({{ $user->id }})" />
                                        <x-admin.action icon="reset" color="amber" label="{{ __('crud_users.reset_password') }}" wire:click="confirmReset({{ $user->id }})" />
                                        <button type="button" wire:click="confirmImpersonate({{ $user->id }})"
                                                title="{{ __('common.impersonate') }}" aria-label="{{ __('common.impersonate') }}"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-indigo-600 transition hover:bg-indigo-50">
                                            @svg('lucide-venetian-mask', 'h-[18px] w-[18px]')
                                        </button>
                                        <x-admin.action icon="delete" color="rose" label="{{ __('common.delete') }}" wire:click="confirmDelete({{ $user->id }})" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-10 text-center text-slate-400">{{ __('crud_users.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal criar/editar --}}
    <x-admin.modal title="{{ $editingId ? __('crud_users.edit_user') : __('crud_users.new_user') }}">
        <form wire:submit="save" class="space-y-4">
            <x-admin.input label="{{ __('crud_users.field_name') }}" name="name" wire:model="name" />
            <x-admin.input label="{{ __('crud_users.field_email') }}" name="email" type="email" wire:model="email" />

            <div class="space-y-1">
                <label for="role_id" class="block text-sm font-medium text-slate-700">{{ __('crud_users.access_role') }}</label>
                <select id="role_id" wire:model="role_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
                    <option value="">{{ __('crud_users.select_placeholder') }}</option>
                    @foreach ($roles as $id => $label)
                        <option value="{{ $id }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('role_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">{{ __('common.cancel') }}</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                    <span wire:loading.remove wire:target="save">{{ __('common.save') }}</span>
                    <span wire:loading wire:target="save">{{ __('common.saving') }}</span>
                </button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
