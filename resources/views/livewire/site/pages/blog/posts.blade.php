<div class="mx-auto max-w-7xl px-6 py-16">
    <header class="text-center">
        <h1 class="text-3xl font-extrabold text-slate-800 sm:text-4xl">Blog</h1>
        <p class="mt-2 text-slate-500">Conteúdos, notícias e reflexões do CaNTIn.</p>
    </header>

    <div class="mt-10 grid gap-8 lg:grid-cols-4">
        {{-- Sidebar --}}
        <aside class="space-y-6 lg:col-span-1">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">{{ __('Search') }}</h2>
                <input type="search" wire:model.live.debounce.250ms="search" placeholder="Digite aqui..."
                       class="mt-3 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Categorias</h2>
                    @if ($selectedCategory)
                        <button wire:click="clearCategory" class="text-xs font-medium text-violet-600 hover:underline">Limpar</button>
                    @endif
                </div>
                <ul class="mt-3 space-y-1">
                    @foreach ($categories as $category)
                        <li>
                            <a href="?category={{ $category->slug }}" wire:navigate
                               class="flex items-center justify-between rounded-lg px-3 py-2 text-sm transition hover:bg-slate-50 {{ $selectedCategory == $category->slug ? 'bg-violet-50 font-semibold text-violet-700' : 'text-slate-700' }}">
                                {{ $category->name }}
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500">{{ $category->posts->count() }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- Posts --}}
        <main class="lg:col-span-3">
            @if ($selectedCategory)
                <div class="mb-4 rounded-lg bg-violet-50 px-4 py-3 text-sm text-violet-700">
                    Categoria: <strong>{{ $categories->firstWhere('slug', $selectedCategory)?->name }}</strong>
                </div>
            @endif

            @if ($posts->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 p-12 text-center text-slate-400">Nenhum post encontrado.</div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($posts as $post)
                        <x-partials.blog.card :post="$post" />
                    @endforeach
                </div>
            @endif

            <div class="mt-8">{{ $posts->links() }}</div>
        </main>
    </div>
</div>
