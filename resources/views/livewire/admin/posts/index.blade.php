<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Posts</h2>
            <p class="text-sm text-slate-500">Gerencie as publicações do blog.</p>
        </div>
        <button wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">
            @svg('lucide-plus', 'h-4 w-4')
            Novo post
        </button>
    </div>


    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Buscar por título..."
                   class="w-full max-w-sm rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Título</th>
                        <th class="px-4 py-3">Publicação</th>
                        <th class="px-4 py-3">Likes</th>
                        <th class="px-4 py-3">Views</th>
                        <th class="px-4 py-3">Autor</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-slate-50" wire:key="post-{{ $post->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $post->id }}</td>
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $post->title }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $post->published_at?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3"><x-admin.badge color="primary">{{ $post->likes_count }}</x-admin.badge></td>
                            <td class="px-4 py-3"><x-admin.badge color="success">{{ $post->views }}</x-admin.badge></td>
                            <td class="px-4 py-3 text-slate-600">{{ $post->user?->name }}</td>
                            <td class="px-4 py-3"><x-admin.badge :color="$post->status?->getColor() ?? 'slate'">{{ $post->status?->label() }}</x-admin.badge></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-admin.action icon="view" color="sky" label="Visualizar" wire:click="view({{ $post->id }})" />
                                    <a href="{{ route('site.blog.show', $post->slug) }}" target="_blank" title="Ver no site" aria-label="Ver no site" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100">
                                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                    </a>
                                    <x-admin.action icon="edit" color="violet" label="Editar" wire:click="edit({{ $post->id }})" />
                                    @if ($post->status === \App\Enum\StatusPost::PUBLISHED)
                                        <x-admin.action icon="unpublish" color="amber" label="Despublicar" wire:click="unpublish({{ $post->id }})" />
                                    @else
                                        <x-admin.action icon="publish" color="emerald" label="Publicar" wire:click="publish({{ $post->id }})" />
                                    @endif
                                    <x-admin.action icon="delete" color="rose" label="Excluir" wire:click="confirmDelete({{ $post->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-10 text-center text-slate-400">Nenhum post encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $posts->links() }}</div>
    </div>

    <x-admin.modal title="{{ $editingId ? 'Editar post' : 'Novo post' }}">
        <form wire:submit="save" class="space-y-4">
            <x-admin.input label="Título" name="titleField" wire:model="titleField" />
            <x-admin.input label="Slug (opcional)" name="slug" wire:model="slug" />

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="space-y-1">
                    <label for="category_id" class="block text-sm font-medium text-slate-700">Categoria</label>
                    <select id="category_id" wire:model="category_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
                        <option value="">Selecione...</option>
                        @foreach ($categories as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <x-admin.input label="Data de publicação" name="published_at" type="date" wire:model="published_at" />
            </div>

            <div class="space-y-1">
                <label for="content" class="block text-sm font-medium text-slate-700">Conteúdo</label>
                <textarea id="content" wire:model="content" rows="6" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500"></textarea>
                @error('content') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1">
                <label for="image" class="block text-sm font-medium text-slate-700">Imagem principal</label>
                @if ($currentImage && ! $image)
                    <p class="text-xs text-slate-500">Atual: {{ $currentImage }}</p>
                @endif
                <input type="file" id="image" wire:model="image" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-violet-700 hover:file:bg-violet-100">
                <div wire:loading wire:target="image" class="text-xs text-slate-400">Enviando imagem...</div>
                @error('image') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
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

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
