<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ $heading }}</h2>
        </div>
        <button wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Novo(a) {{ $singular }}
        </button>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Buscar..."
                   class="w-full max-w-sm rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        @foreach ($fields as $name => $cfg)
                            <th class="px-4 py-3">{{ $cfg['label'] ?? \Illuminate\Support\Str::headline($name) }}</th>
                        @endforeach
                        @if ($hasStatus)<th class="px-4 py-3">Status</th>@endif
                        <th class="px-4 py-3 text-right">Ações</th>
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
                                    <x-admin.action icon="view" color="sky" label="Visualizar" wire:click="view({{ $record->id }})" />
                                    <x-admin.action icon="edit" color="violet" label="Editar" wire:click="edit({{ $record->id }})" />
                                    @if ($hasStatus)
                                        <x-admin.action icon="toggle"
                                            :color="$record->status === \App\Enum\Status::ACTIVE ? 'amber' : 'emerald'"
                                            :label="$record->status === \App\Enum\Status::ACTIVE ? 'Inativar' : 'Ativar'"
                                            wire:click="confirmToggle({{ $record->id }})" />
                                    @endif
                                    <x-admin.action icon="delete" color="rose" label="Excluir" wire:click="confirmDelete({{ $record->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($fields) + ($hasStatus ? 3 : 2) }}" class="px-4 py-10 text-center text-slate-400">Nenhum registro encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $records->links() }}</div>
    </div>

    <x-admin.modal title="{{ $editingId ? 'Editar '.$singular : 'Novo(a) '.$singular }}">
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
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">Salvar</button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
