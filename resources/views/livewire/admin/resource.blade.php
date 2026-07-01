<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ $heading }}</h2>
        </div>
        @php $newLabel = __('crud_resource.new').' '.$singular; @endphp
        @if ($usesPageEditor && $createRoute)
            <a href="{{ route($createRoute) }}" wire:navigate title="{{ $newLabel }}" aria-label="{{ $newLabel }}"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700 sm:px-4">
                @svg('lucide-plus', 'h-4 w-4')
                <span class="hidden sm:inline">{{ $newLabel }}</span>
            </a>
        @else
            <button wire:click="create" title="{{ $newLabel }}" aria-label="{{ $newLabel }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700 sm:px-4">
                @svg('lucide-plus', 'h-4 w-4')
                <span class="hidden sm:inline">{{ $newLabel }}</span>
            </button>
        @endif
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <x-admin.table-toolbar :options="$this->perPageOptions()" :placeholder="__('crud_resource.search_placeholder')" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <x-admin.th column="id" :sort-field="$sortField" :sort-direction="$sortDirection">#</x-admin.th>
                        @foreach ($fields as $name => $cfg)
                            <x-admin.th column="{{ $name }}" :sort-field="$sortField" :sort-direction="$sortDirection">{{ $cfg['label'] ?? \Illuminate\Support\Str::headline($name) }}</x-admin.th>
                        @endforeach
                        @if ($hasStatus)<x-admin.th column="status" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('common.status') }}</x-admin.th>@endif
                        <th class="px-4 py-3 text-right">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($records as $record)
                        <tr class="hover:bg-slate-50" wire:key="rec-{{ $record->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $record->id }}</td>
                            @foreach ($fields as $name => $cfg)
                                <td class="px-4 py-3 text-slate-700">{{ \Illuminate\Support\Str::limit(strip_tags((string) $record->{$name}), 60) }}</td>
                            @endforeach
                            @if ($hasStatus)
                                <td class="px-4 py-3">
                                    <x-admin.badge :color="$record->status?->getColor() ?? 'slate'">{{ $record->status?->label() ?? '—' }}</x-admin.badge>
                                </td>
                            @endif
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-admin.action icon="view" color="sky" label="{{ __('common.view') }}" wire:click="view({{ $record->id }})" />
                                    @if ($usesPageEditor && $editRoute)
                                        <a href="{{ route($editRoute, $record) }}" wire:navigate title="{{ __('common.edit') }}" aria-label="{{ __('common.edit') }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-violet-600 transition hover:bg-violet-50">
                                            @svg('lucide-pencil', 'h-[18px] w-[18px]')
                                        </a>
                                    @else
                                        <x-admin.action icon="edit" color="violet" label="{{ __('common.edit') }}" wire:click="edit({{ $record->id }})" />
                                    @endif
                                    @if ($hasStatus)
                                        <x-admin.action icon="toggle"
                                            :color="$record->status === \App\Enum\Status::ACTIVE ? 'amber' : 'emerald'"
                                            :label="$record->status === \App\Enum\Status::ACTIVE ? __('common.deactivate') : __('common.activate')"
                                            wire:click="confirmToggle({{ $record->id }})" />
                                    @endif
                                    <x-admin.action icon="delete" color="rose" label="{{ __('common.delete') }}" wire:click="confirmDelete({{ $record->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($fields) + ($hasStatus ? 3 : 2) }}" class="px-4 py-10 text-center text-slate-400">{{ __('crud_resource.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $records->links() }}</div>
    </div>

    @unless ($usesPageEditor)
        <x-admin.modal title="{{ $editingId ? __('crud_resource.edit_title', ['singular' => $singular]) : __('crud_resource.new_title', ['singular' => $singular]) }}">
            <form wire:submit="save" class="space-y-4">
                @foreach ($fields as $name => $cfg)
                    <div class="space-y-1">
                        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">{{ $cfg['label'] ?? \Illuminate\Support\Str::headline($name) }}</label>
                        @if (($cfg['type'] ?? 'text') === 'textarea')
                            <textarea id="{{ $name }}" wire:model="form.{{ $name }}" rows="5" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
                        @else
                            <input type="{{ $cfg['type'] ?? 'text' }}" id="{{ $name }}" wire:model="form.{{ $name }}" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
                        @endif
                        @error("form.{$name}") <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                @endforeach

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">{{ __('common.cancel') }}</button>
                    <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">{{ __('common.save') }}</button>
                </div>
            </form>
        </x-admin.modal>
    @endunless

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
