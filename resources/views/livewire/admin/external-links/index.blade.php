<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Links Externos</h2>
        </div>
        <button wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">
            @svg('lucide-plus', 'h-4 w-4')
            Novo link
        </button>
    </div>


    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Buscar por título ou URL..."
                   class="w-full max-w-sm rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Título</th>
                        <th class="px-4 py-3">URL</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Ações</th>
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
                                    <x-admin.action icon="view" color="sky" label="Visualizar" wire:click="view({{ $link->id }})" />
                                    <x-admin.action icon="edit" color="violet" label="Editar" wire:click="edit({{ $link->id }})" />
                                    <x-admin.action icon="toggle"
                                        :color="$link->status === \App\Enum\Status::ACTIVE ? 'amber' : 'emerald'"
                                        :label="$link->status === \App\Enum\Status::ACTIVE ? 'Inativar' : 'Ativar'"
                                        wire:click="confirmToggle({{ $link->id }})" />
                                    <x-admin.action icon="delete" color="rose" label="Excluir" wire:click="confirmDelete({{ $link->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">Nenhum link encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $links->links() }}</div>
    </div>

    <x-admin.modal title="{{ $editingId ? 'Editar link' : 'Novo link' }}">
        <form wire:submit="save" class="space-y-4">
            <x-admin.input label="Título" name="title" wire:model="title" />
            <x-admin.input label="Slug (opcional)" name="slug" wire:model="slug" />
            <div class="space-y-1">
                <label class="block text-sm font-medium text-slate-700">Tipo do link</label>
                <select wire:model="type_external_link_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach ($types as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
                </select>
                @error('type_external_link_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <x-admin.input label="URL" name="url" type="url" wire:model="url" />
            <x-admin.input label="Descrição" name="description" wire:model="description" />

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">Salvar</button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
