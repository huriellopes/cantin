<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Comentários</h2>
        <p class="text-sm text-slate-500">Modere e responda os comentários do blog.</p>
    </div>

    @if (session('status'))
        <div class="rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Buscar no conteúdo..."
                   class="w-full max-w-sm rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Comentário</th>
                        <th class="px-4 py-3">Usuário</th>
                        <th class="px-4 py-3">Post</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($comments as $comment)
                        <tr class="hover:bg-slate-50" wire:key="comment-{{ $comment->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $comment->id }}</td>
                            <td class="px-4 py-3 max-w-xs text-slate-700">{{ \Illuminate\Support\Str::limit($comment->body, 80) }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $comment->user?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $comment->post?->title }}</td>
                            <td class="px-4 py-3">
                                <x-admin.badge :color="$comment->status?->getColor() ?? 'slate'">{{ $comment->status?->label() }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2 text-xs">
                                    @if ($comment->post)
                                        <a href="{{ route('site.blog.show', $comment->post->slug) }}" target="_blank" class="rounded px-2 py-1 text-sky-600 hover:bg-sky-50">Ver post</a>
                                    @endif
                                    <button wire:click="reply({{ $comment->id }})" class="rounded px-2 py-1 text-violet-600 hover:bg-violet-50">Responder</button>
                                    <button wire:click="toggleStatus({{ $comment->id }})" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-100">
                                        {{ $comment->status === \App\Enum\Status::ACTIVE ? 'Inativar' : 'Ativar' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">Nenhum comentário encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $comments->links() }}</div>
    </div>

    <x-admin.modal title="Responder comentário">
        <form wire:submit="save" class="space-y-4">
            <div class="space-y-1">
                <label class="block text-sm font-medium text-slate-700">Comentário original</label>
                <div class="rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-600">{{ $originalBody }}</div>
            </div>
            <div class="space-y-1">
                <label for="body" class="block text-sm font-medium text-slate-700">Sua resposta</label>
                <textarea id="body" wire:model="body" rows="4" maxlength="500"
                          class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
                @error('body') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">Responder</button>
            </div>
        </form>
    </x-admin.modal>
</div>
