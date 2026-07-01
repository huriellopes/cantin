<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ __('crud_partner_entities.title') }}</h2>
        </div>
        <button wire:click="create" title="{{ __('crud_partner_entities.new_entity') }}" aria-label="{{ __('crud_partner_entities.new_entity') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700 sm:px-4">
            @svg('lucide-plus', 'h-4 w-4')
            <span class="hidden sm:inline">{{ __('crud_partner_entities.new_entity') }}</span>
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
                        <x-admin.th column="name" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_partner_entities.col_name') }}</x-admin.th>
                        <x-admin.th column="phone" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_partner_entities.col_phone') }}</x-admin.th>
                        <th class="px-4 py-3">{{ __('crud_partner_entities.col_city_state') }}</th>
                        <x-admin.th column="status" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('common.status') }}</x-admin.th>
                        <th class="px-4 py-3 text-right">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($entities as $entity)
                        <tr class="hover:bg-slate-50" wire:key="pe-{{ $entity->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $entity->id }}</td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-slate-700">{{ $entity->name }}</span>
                                <span class="block text-xs text-slate-400">{{ $entity->email }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $entity->phone }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ collect([$entity->address?->city?->name, $entity->address?->state?->abbr])->filter()->implode('/') ?: '—' }}</td>
                            <td class="px-4 py-3"><x-admin.badge :color="$entity->status?->getColor() ?? 'slate'">{{ $entity->status?->label() }}</x-admin.badge></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-admin.action icon="view" color="sky" label="{{ __('common.view') }}" wire:click="view({{ $entity->id }})" />
                                    <x-admin.action icon="edit" color="violet" label="{{ __('common.edit') }}" wire:click="edit({{ $entity->id }})" />
                                    <x-admin.action icon="toggle"
                                        :color="$entity->status === \App\Enum\Status::ACTIVE ? 'amber' : 'emerald'"
                                        :label="$entity->status === \App\Enum\Status::ACTIVE ? __('common.deactivate') : __('common.activate')"
                                        wire:click="confirmToggle({{ $entity->id }})" />
                                    <x-admin.action icon="delete" color="rose" label="{{ __('common.delete') }}" wire:click="confirmDelete({{ $entity->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">{{ __('crud_partner_entities.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $entities->links() }}</div>
    </div>

    <x-admin.modal title="{{ $form->editingId ? __('crud_partner_entities.edit_entity') : __('crud_partner_entities.new_entity') }}">
        <form wire:submit="save" class="space-y-6">
            <section class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-400">{{ __('crud_partner_entities.section_data') }}</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-admin.input label="{{ __('crud_partner_entities.field_name') }}" name="form.name" wire:model="form.name" />
                    <x-admin.input label="{{ __('crud_partner_entities.field_email') }}" name="form.email" type="email" wire:model="form.email" />
                    <x-admin.input label="{{ __('crud_partner_entities.field_phone') }}" name="form.phone" wire:model="form.phone" x-mask="(99) 9 9999-9999" />
                </div>
                <div class="space-y-1">
                    <label for="activity_carried_out" class="block text-sm font-medium text-slate-700">{{ __('crud_partner_entities.field_activities') }}</label>
                    <textarea id="activity_carried_out" wire:model="form.activity_carried_out" rows="3" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
                    @error('form.activity_carried_out') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label for="image" class="block text-sm font-medium text-slate-700">{{ __('crud_partner_entities.field_image') }}</label>
                    @if ($currentImage && ! $form->image)<p class="text-xs text-slate-500">{{ __('crud_partner_entities.current_image') }} {{ $currentImage }}</p>@endif
                    <input type="file" id="image" wire:model="form.image" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-violet-700 hover:file:bg-violet-100">
                    <div wire:loading wire:target="image" class="text-xs text-slate-400">{{ __('crud_partner_entities.uploading_image') }}</div>
                    @error('form.image') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
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
