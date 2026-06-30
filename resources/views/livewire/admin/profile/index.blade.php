<div class="mx-auto max-w-3xl space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Meu perfil</h1>
        <p class="mt-1 text-sm text-slate-500">Atualize seus dados de acesso e gerencie sua conta.</p>
    </div>

    {{-- Dados pessoais --}}
    <form wire:submit="updateProfile" class="space-y-5 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">Dados pessoais</h2>
        <x-admin.input label="Nome" name="name" wire:model="name" />
        <x-admin.input label="E-mail" name="email" type="email" wire:model="email" />
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-700">
                @svg('lucide-save', 'h-4 w-4') Salvar
            </button>
        </div>
    </form>

    {{-- Alterar senha --}}
    <form wire:submit="updatePassword" class="space-y-5 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">Alterar senha</h2>
        <x-admin.input label="Senha atual" name="current_password" type="password" wire:model="current_password" autocomplete="current-password" />
        <x-admin.input label="Nova senha" name="password" type="password" wire:model="password" autocomplete="new-password" />
        <x-admin.input label="Confirmar nova senha" name="password_confirmation" type="password" wire:model="password_confirmation" autocomplete="new-password" />
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-700">
                @svg('lucide-key-round', 'h-4 w-4') Atualizar senha
            </button>
        </div>
    </form>

    {{-- Zona de risco --}}
    <div class="space-y-4 rounded-2xl border border-rose-200 bg-rose-50/50 p-6">
        <div>
            <h2 class="text-lg font-semibold text-rose-700">Excluir conta</h2>
            <p class="mt-1 text-sm text-rose-600/80">Esta ação é permanente. Todos os seus dados de acesso serão removidos e você será desconectado.</p>
        </div>
        <button type="button" wire:click="confirmDeleteAccount" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
            @svg('lucide-trash-2', 'h-4 w-4') Excluir minha conta
        </button>
    </div>

    {{-- Modal de confirmação por senha --}}
    <x-admin.modal show="showDelete" title="Confirmar exclusão de conta">
        <form wire:submit="deleteAccount" class="space-y-4">
            <p class="text-sm text-slate-600">Para confirmar, digite sua senha. <strong>Esta ação não pode ser desfeita.</strong></p>
            <x-admin.input label="Sua senha" name="delete_password" type="password" wire:model="delete_password" autocomplete="current-password" />
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="$wire.showDelete = false" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Cancelar</button>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                    @svg('lucide-trash-2', 'h-4 w-4') Excluir definitivamente
                </button>
            </div>
        </form>
    </x-admin.modal>
</div>
