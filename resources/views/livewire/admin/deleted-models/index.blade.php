<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Modelos Excluídos</h2>
        <p class="text-sm text-slate-500">Restaure registros excluídos ou remova-os permanentemente.</p>
    </div>

    @if (session('status'))
        <div class="rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Buscar por modelo..."
                   class="w-full max-w-sm rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Modelo</th>
                        <th class="px-4 py-3">Chave</th>
                        <th class="px-4 py-3">Excluído em</th>
                        <th class="px-4 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($records as $record)
                        <tr class="hover:bg-slate-50" wire:key="dm-{{ $record->id }}">
                            <td class="px-4 py-3 font-medium text-slate-700">{{ class_basename($record->model) }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $record->key }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $record->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2 text-xs">
                                    <button wire:click="toggleView({{ $record->id }})" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-100">Dados</button>
                                    <button wire:click="restore({{ $record->id }})" class="rounded px-2 py-1 text-emerald-600 hover:bg-emerald-50">Restaurar</button>
                                    <button wire:click="forceDelete({{ $record->id }})" wire:confirm="Remover permanentemente? Esta ação não pode ser desfeita." class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50">Excluir</button>
                                </div>
                            </td>
                        </tr>
                        @if ($viewingId === $record->id)
                            <tr wire:key="dm-values-{{ $record->id }}">
                                <td colspan="4" class="bg-slate-50 px-4 py-3">
                                    <pre class="max-h-64 overflow-auto rounded-lg bg-slate-900 p-4 text-xs text-slate-100">{{ json_encode($record->values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr><td colspan="4" class="px-4 py-10 text-center text-slate-400">Nenhum registro excluído.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $records->links() }}</div>
    </div>
</div>
