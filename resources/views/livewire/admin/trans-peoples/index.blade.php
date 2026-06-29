<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Pessoas Trans</h2>
        </div>
        <button wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Novo cadastro
        </button>
    </div>

    @if (session('status'))
        <div class="rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

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
                        <th class="px-4 py-3">Cidade/UF</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($people as $person)
                        <tr class="hover:bg-slate-50" wire:key="tp-{{ $person->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $person->id }}</td>
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $person->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $person->email }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $person->address?->city?->name }}{{ $person->address?->state ? ' / '.$person->address->state->name : '' }}</td>
                            <td class="px-4 py-3"><x-admin.badge :color="$person->status?->getColor() ?? 'slate'">{{ $person->status?->label() }}</x-admin.badge></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2 text-xs">
                                    <button wire:click="edit({{ $person->id }})" class="rounded px-2 py-1 text-violet-600 hover:bg-violet-50">Editar</button>
                                    <button wire:click="toggleStatus({{ $person->id }})" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-100">
                                        {{ $person->status === \App\Enum\Status::ACTIVE ? 'Inativar' : 'Ativar' }}
                                    </button>
                                    <button wire:click="delete({{ $person->id }})" wire:confirm="Excluir este cadastro?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50">Excluir</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">Nenhum cadastro encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $people->links() }}</div>
    </div>

    <x-admin.modal title="{{ $editingId ? 'Editar cadastro' : 'Novo cadastro' }}">
        <form wire:submit="save" class="space-y-6">
            <section class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Dados pessoais</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-admin.input label="Nome" name="name" wire:model="name" />
                    <x-admin.input label="E-mail" name="email" type="email" wire:model="email" />
                    <x-admin.input label="Telefone" name="phone" wire:model="phone" x-mask="(99) 9 9999-9999" />
                </div>
            </section>

            <x-admin.address-fields :states="$states" :cities="$cities" />

            <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">Salvar</button>
            </div>
        </form>
    </x-admin.modal>
</div>
