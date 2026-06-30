<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Entidades Parceiras</h2>
        </div>
        <button wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Nova entidade
        </button>
    </div>


    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Buscar por nome ou e-mail..."
                   class="w-full max-w-sm rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">E-mail</th>
                        <th class="px-4 py-3">Telefone</th>
                        <th class="px-4 py-3">Cidade/UF</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($entities as $entity)
                        <tr class="hover:bg-slate-50" wire:key="pe-{{ $entity->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $entity->id }}</td>
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $entity->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $entity->email }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $entity->phone }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $entity->address?->city?->name }}{{ $entity->address?->state ? ' / '.$entity->address->state->name : '' }}</td>
                            <td class="px-4 py-3"><x-admin.badge :color="$entity->status?->getColor() ?? 'slate'">{{ $entity->status?->label() }}</x-admin.badge></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-admin.action icon="view" color="sky" label="Visualizar" wire:click="view({{ $entity->id }})" />
                                    <x-admin.action icon="edit" color="violet" label="Editar" wire:click="edit({{ $entity->id }})" />
                                    <x-admin.action icon="toggle"
                                        :color="$entity->status === \App\Enum\Status::ACTIVE ? 'amber' : 'emerald'"
                                        :label="$entity->status === \App\Enum\Status::ACTIVE ? 'Inativar' : 'Ativar'"
                                        wire:click="confirmToggle({{ $entity->id }})" />
                                    <x-admin.action icon="delete" color="rose" label="Excluir" wire:click="confirmDelete({{ $entity->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-10 text-center text-slate-400">Nenhuma entidade encontrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $entities->links() }}</div>
    </div>

    <x-admin.modal title="{{ $editingId ? 'Editar entidade' : 'Nova entidade' }}">
        <form wire:submit="save" class="space-y-6">
            <section class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Dados</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-admin.input label="Nome" name="name" wire:model="name" />
                    <x-admin.input label="E-mail" name="email" type="email" wire:model="email" />
                    <x-admin.input label="Telefone" name="phone" wire:model="phone" x-mask="(99) 9 9999-9999" />
                </div>
                <div class="space-y-1">
                    <label for="activity_carried_out" class="block text-sm font-medium text-slate-700">Atividades realizadas</label>
                    <textarea id="activity_carried_out" wire:model="activity_carried_out" rows="3" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
                    @error('activity_carried_out') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label for="image" class="block text-sm font-medium text-slate-700">Imagem</label>
                    @if ($currentImage && ! $image)<p class="text-xs text-slate-500">Atual: {{ $currentImage }}</p>@endif
                    <input type="file" id="image" wire:model="image" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-violet-700 hover:file:bg-violet-100">
                    <div wire:loading wire:target="image" class="text-xs text-slate-400">Enviando imagem...</div>
                    @error('image') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </section>

            <x-admin.address-fields :states="$states" :cities="$cities" />

            <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">Salvar</button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
