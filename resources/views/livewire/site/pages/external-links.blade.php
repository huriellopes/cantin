<div class="mx-auto max-w-7xl px-6 py-16">
    <header class="text-center">
        <h1 class="text-3xl font-extrabold text-slate-800 sm:text-4xl">Links úteis</h1>
        <p class="mt-2 text-slate-500">Serviços, canais de apoio e conteúdos selecionados.</p>
    </header>

    <div class="mt-10 grid gap-8 lg:grid-cols-4">
        {{-- Sidebar --}}
        <aside class="space-y-6 lg:col-span-1">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Pesquisar</h2>
                <input type="search" wire:model.live.debounce.400ms="search" placeholder="Pesquisar..."
                       class="mt-3 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Tipos de links</h2>
                    @if ($selectedLinkType)
                        <button wire:click="clearLinkType" class="text-xs font-medium text-violet-600 hover:underline">Limpar</button>
                    @endif
                </div>
                <ul class="mt-3 space-y-1">
                    @foreach ($types as $type)
                        <li>
                            <a href="?type={{ $type->slug }}" wire:navigate
                               class="flex items-center justify-between rounded-lg px-3 py-2 text-sm transition hover:bg-slate-50 {{ $selectedLinkType === $type->slug ? 'bg-violet-50 font-semibold text-violet-700' : 'text-slate-700' }}">
                                {{ $type->name }}
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500">{{ $type->links->count() }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- Lista --}}
        <div class="lg:col-span-3">
            @if ($links->count() > 0)
                <div class="grid gap-5 sm:grid-cols-2">
                    @foreach ($links as $link)
                        <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                            <h3 class="text-lg font-bold text-slate-800">{{ $link->title }}</h3>
                            <p class="mt-2 flex-1 text-sm text-slate-600">{{ $link->description }}</p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-medium text-violet-700">{{ $link->type?->name }}</span>
                                <a href="{{ $link->url }}" target="_blank" rel="noopener"
                                   class="rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-4 py-1.5 text-sm font-semibold text-white transition hover:brightness-110">Acessar</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $links->links() }}</div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 p-12 text-center text-slate-400">Nenhum link encontrado.</div>
            @endif
        </div>
    </div>
</div>
