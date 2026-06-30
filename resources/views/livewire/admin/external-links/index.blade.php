<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ __('crud_external_links.title') }}</h2>
        </div>
        <button wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">
            @svg('lucide-plus', 'h-4 w-4')
            {{ __('crud_external_links.new_link') }}
        </button>
    </div>


    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <x-admin.table-toolbar :options="$this->perPageOptions()" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <x-admin.th column="id" :sort-field="$sortField" :sort-direction="$sortDirection">#</x-admin.th>
                        <x-admin.th column="title" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_external_links.column_title') }}</x-admin.th>
                        <x-admin.th column="url" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_external_links.column_url') }}</x-admin.th>
                        <th class="px-4 py-3">{{ __('crud_external_links.column_type') }}</th>
                        <x-admin.th column="status" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('common.status') }}</x-admin.th>
                        <th class="px-4 py-3 text-right">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($links as $link)
                        <tr class="hover:bg-slate-50" wire:key="link-{{ $link->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $link->id }}</td>
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $link->title }}</td>
                            <td class="px-4 py-3 max-w-xs truncate text-sky-600"><a href="{{ $link->url }}" target="_blank" class="hover:underline">{{ $link->url }}</a></td>
                            <td class="px-4 py-3 text-slate-600">{{ $link->type?->name }}</td>
                            <td class="px-4 py-3"><x-admin.badge :color="$link->status?->getColor() ?? 'slate'">{{ $link->status?->label() }}</x-admin.badge></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-admin.action icon="view" color="sky" label="{{ __('common.view') }}" wire:click="view({{ $link->id }})" />
                                    <x-admin.action icon="edit" color="violet" label="{{ __('common.edit') }}" wire:click="edit({{ $link->id }})" />
                                    <x-admin.action icon="toggle"
                                        :color="$link->status === \App\Enum\Status::ACTIVE ? 'amber' : 'emerald'"
                                        :label="$link->status === \App\Enum\Status::ACTIVE ? __('common.deactivate') : __('common.activate')"
                                        wire:click="confirmToggle({{ $link->id }})" />
                                    <x-admin.action icon="delete" color="rose" label="{{ __('common.delete') }}" wire:click="confirmDelete({{ $link->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">{{ __('crud_external_links.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $links->links() }}</div>
    </div>

    <x-admin.modal title="{{ $editingId ? __('crud_external_links.modal_edit') : __('crud_external_links.modal_new') }}">
        <form wire:submit="save" class="space-y-4">
            <x-admin.input label="{{ __('crud_external_links.field_title') }}" name="title" wire:model="title" />
            <x-admin.input label="{{ __('crud_external_links.field_slug') }}" name="slug" wire:model="slug" />
            <div class="space-y-1">
                <label class="block text-sm font-medium text-slate-700">{{ __('crud_external_links.field_type') }}</label>
                <select wire:model="type_external_link_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">{{ __('crud_external_links.select_placeholder') }}</option>
                    @foreach ($types as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
                </select>
                @error('type_external_link_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <x-admin.input label="{{ __('crud_external_links.field_url') }}" name="url" type="url" wire:model="url" />
            <x-admin.input label="{{ __('crud_external_links.field_description') }}" name="description" wire:model="description" />

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">{{ __('common.cancel') }}</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">{{ __('common.save') }}</button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
