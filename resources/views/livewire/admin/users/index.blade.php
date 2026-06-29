<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Usuários</h2>
            <p class="text-sm text-slate-500">Gerencie os acessos ao painel.</p>
        </div>
        <button wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Novo usuário
        </button>
    </div>

    @if (session('status'))
        <div class="rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    @if ($generatedPassword)
        <div class="rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Nova senha de <strong>{{ $generatedFor }}</strong>:
            <code class="rounded bg-amber-100 px-2 py-0.5 font-mono">{{ $generatedPassword }}</code>
            <button wire:click="$set('generatedPassword', null)" class="ml-2 underline">ocultar</button>
        </div>
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
                        <th class="px-4 py-3">Perfil</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Ações</th>
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
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2 text-xs">
                                    <button wire:click="edit({{ $user->id }})" class="rounded px-2 py-1 text-violet-600 hover:bg-violet-50">Editar</button>
                                    <button wire:click="toggleStatus({{ $user->id }})" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-100">
                                        {{ $user->status === \App\Enum\Status::ACTIVE ? 'Inativar' : 'Ativar' }}
                                    </button>
                                    <button wire:click="resetPassword({{ $user->id }})" wire:confirm="Gerar uma nova senha para este usuário?" class="rounded px-2 py-1 text-amber-600 hover:bg-amber-50">Resetar senha</button>
                                    <button wire:click="delete({{ $user->id }})" wire:confirm="Excluir este usuário?" class="rounded px-2 py-1 text-rose-600 hover:bg-rose-50">Excluir</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">Nenhum usuário encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal criar/editar --}}
    <x-admin.modal title="{{ $editingId ? 'Editar usuário' : 'Novo usuário' }}">
        <form wire:submit="save" class="space-y-4">
            <x-admin.input label="Nome" name="name" wire:model="name" />
            <x-admin.input label="E-mail" name="email" type="email" wire:model="email" />

            <div class="space-y-1">
                <label for="role_id" class="block text-sm font-medium text-slate-700">Perfil de acesso</label>
                <select id="role_id" wire:model="role_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
                    <option value="">Selecione...</option>
                    @foreach ($roles as $id => $label)
                        <option value="{{ $id }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('role_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                    <span wire:loading.remove wire:target="save">Salvar</span>
                    <span wire:loading wire:target="save">Salvando...</span>
                </button>
            </div>
        </form>
    </x-admin.modal>
</div>
