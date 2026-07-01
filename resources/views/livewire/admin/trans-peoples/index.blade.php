<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ __('crud_trans_peoples.title') }}</h2>
        </div>
        <button wire:click="create" title="{{ __('crud_trans_peoples.new_record') }}" aria-label="{{ __('crud_trans_peoples.new_record') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700 sm:px-4">
            @svg('lucide-plus', 'h-4 w-4')
            <span class="hidden sm:inline">{{ __('crud_trans_peoples.new_record') }}</span>
        </button>
    </div>


    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <x-admin.table-toolbar :options="$this->perPageOptions()" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <x-admin.th column="id" :sort-field="$sortField" :sort-direction="$sortDirection">#</x-admin.th>
                        <x-admin.th column="name" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_trans_peoples.column_name') }}</x-admin.th>
                        <th class="px-4 py-3">{{ __('crud_trans_peoples.column_city_state') }}</th>
                        <x-admin.th column="status" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('common.status') }}</x-admin.th>
                        <th class="px-4 py-3 text-right">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($people as $person)
                        <tr class="hover:bg-slate-50" wire:key="tp-{{ $person->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $person->id }}</td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-slate-700">{{ $person->name }}</span>
                                <span class="block text-xs text-slate-400">{{ $person->email }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ collect([$person->address?->city?->name, $person->address?->state?->abbr])->filter()->implode('/') ?: '—' }}</td>
                            <td class="px-4 py-3"><x-admin.badge :color="$person->status?->getColor() ?? 'slate'">{{ $person->status?->label() }}</x-admin.badge></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-admin.action icon="view" color="sky" label="{{ __('common.view') }}" wire:click="view({{ $person->id }})" />
                                    <x-admin.action icon="edit" color="violet" label="{{ __('common.edit') }}" wire:click="edit({{ $person->id }})" />
                                    <x-admin.action icon="toggle"
                                        :color="$person->status === \App\Enum\Status::ACTIVE ? 'amber' : 'emerald'"
                                        :label="$person->status === \App\Enum\Status::ACTIVE ? __('common.deactivate') : __('common.activate')"
                                        wire:click="confirmToggle({{ $person->id }})" />
                                    <x-admin.action icon="delete" color="rose" label="{{ __('common.delete') }}" wire:click="confirmDelete({{ $person->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">{{ __('crud_trans_peoples.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $people->links() }}</div>
    </div>

    <x-admin.modal title="{{ $form->editingId ? __('crud_trans_peoples.edit_record') : __('crud_trans_peoples.new_record') }}">
        <form wire:submit="save" class="space-y-6">
            <section class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-400">{{ __('crud_trans_peoples.personal_data') }}</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-admin.input label="{{ __('crud_trans_peoples.field_name') }}" name="form.name" wire:model="form.name" />
                    <x-admin.input label="{{ __('crud_trans_peoples.field_email') }}" name="form.email" type="email" wire:model="form.email" />
                    <x-admin.input label="{{ __('crud_trans_peoples.field_phone') }}" name="form.phone" wire:model="form.phone" x-mask="(99) 9 9999-9999" />
                </div>
            </section>

            <x-admin.address-fields :states="$states" :cities="$cities" />

            <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">{{ __('common.cancel') }}</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">{{ __('common.save') }}</button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
