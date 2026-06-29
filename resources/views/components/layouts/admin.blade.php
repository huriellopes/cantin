<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Painel' }} · CaNTIn</title>
    {{-- Aplica o estado da sidebar antes da pintura (evita flicker no reload) --}}
    <script>
        if (localStorage.getItem('sidebarCollapsed') === '1') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-slate-100 text-slate-800 antialiased">
<div
    x-data="{
        sidebarOpen: false,
        collapsed: document.documentElement.classList.contains('sidebar-collapsed'),
        tip: { show: false, text: '', y: 0 },
        toggleCollapse() {
            this.collapsed = ! this.collapsed;
            document.documentElement.classList.toggle('sidebar-collapsed', this.collapsed);
            localStorage.setItem('sidebarCollapsed', this.collapsed ? '1' : '0');
            this.tip.show = false;
        },
        showTip(el, text) { if (! this.collapsed) return; const r = el.getBoundingClientRect(); this.tip = { show: true, text, y: r.top + r.height / 2 } }
    }"
    class="min-h-full"
>
    @php
        $i = [
            'home' => 'M2.25 12 11.2 3.05a1.13 1.13 0 0 1 1.6 0L21.75 12M4.5 9.75V20.1c0 .51.4.9.9.9h3.6v-6h6v6h3.6c.5 0 .9-.39.9-.9V9.75',
            'building' => 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21',
            'globe' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.95 8.95 0 0 0 4.5-1.2M12 21a8.95 8.95 0 0 1-4.5-1.2M3.6 9h16.8M3.6 15h16.8M12 3a13 13 0 0 1 0 18M12 3a13 13 0 0 0 0 18',
            'grid' => 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25Zm9.75-9.75A2.25 2.25 0 0 1 15.75 3.75H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z',
            'users' => 'M15 19.1a9.4 9.4 0 0 0 6.7-2.6 4.1 4.1 0 0 0-7.5-2.5M15 19.1c0-1.1-.3-2.2-.8-3.1M15 19.1v.1A12.3 12.3 0 0 1 8.6 21c-2.3 0-4.5-.6-6.4-1.8v-.1a6.4 6.4 0 0 1 12-3.1M12 6.4a3.4 3.4 0 1 1-6.8 0 3.4 3.4 0 0 1 6.8 0Zm8.3 2.2a2.6 2.6 0 1 1-5.3 0 2.6 2.6 0 0 1 5.3 0Z',
            'user' => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.1a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75c-2.7 0-5.2-.6-7.5-1.65Z',
            'heart' => 'M21 8.25c0-2.5-2-4.5-4.5-4.5-1.74 0-3.25 1-4 2.45-.75-1.45-2.26-2.45-4-2.45a4.5 4.5 0 0 0-4.5 4.5c0 6 8.5 10.5 8.5 10.5S21 14.25 21 8.25Z',
            'doc' => 'M19.5 14.25v-2.6c0-1.13-.46-2.21-1.27-3l-3.38-3.38a4.25 4.25 0 0 0-3-1.27H6.75A2.25 2.25 0 0 0 4.5 6.25v13.5A2.25 2.25 0 0 0 6.75 21h10.5a2.25 2.25 0 0 0 2.25-2.25v-4.5ZM9 9h1.5m-1.5 3h6m-6 3h6',
            'tag' => 'M9.57 2.25a3 3 0 0 0-2.12.88L2.84 7.74a3 3 0 0 0 0 4.24l7.18 7.18a3 3 0 0 0 4.24 0l4.6-4.6a3 3 0 0 0 .89-2.13V5.25a3 3 0 0 0-3-3H9.57ZM7.5 7.5h.01',
            'chat' => 'M7.5 8.25h9m-9 3H12m-9.75 1.5c0 1.6 1.12 3 2.7 3.23 1.09.16 2.19.28 3.3.37V21l4.18-4.18c.21-.2.49-.32.78-.33a48 48 0 0 0 5.83-.5c1.59-.23 2.71-1.62 2.71-3.23V6.74c0-1.6-1.12-3-2.71-3.23A48.4 48.4 0 0 0 12 3c-2.39 0-4.74.18-7.04.51-1.59.23-2.71 1.62-2.71 3.23v6Z',
            'link' => 'M13.19 8.69a4.5 4.5 0 0 1 0 6.36l-3 3a4.5 4.5 0 0 1-6.36-6.36l1.5-1.5m9.92 1.42a4.5 4.5 0 0 0 0-6.36l-3-3a4.5 4.5 0 0 0-6.36 6.36l1.5 1.5',
            'trash' => 'm14.74 9-.35 9m-4.78 0L9.26 9M19.2 5.79 18.16 19.6a2.25 2.25 0 0 1-2.24 2.15H8.08a2.25 2.25 0 0 1-2.24-2.15L4.8 5.79m14.4 0a48 48 0 0 0-3.48-.4m3.48.4a48 48 0 0 1 .98.05M4.8 5.79c.32-.06.65-.12.98-.17m0 0a48 48 0 0 1 3.48-.4m7.5 0v-.91A1.75 1.75 0 0 0 14.5 2.6h-5a1.75 1.75 0 0 0-1.74 1.67v.91m7.48 0a48 48 0 0 0-7.48 0',
        ];
        $nav = [
            ['Painel', route('admin.dashboard'), request()->routeIs('admin.dashboard'), $i['home']],
            ['Terreiros', route('admin.terreiros.index'), request()->routeIs('admin.terreiros.*'), $i['building']],
            ['Nações', route('admin.nations.index'), request()->routeIs('admin.nations.*'), $i['globe']],
            ['Tipos de Terreiro', route('admin.type-terreiros.index'), request()->routeIs('admin.type-terreiros.*'), $i['grid']],
            ['Gêneros', route('admin.type-peoples.index'), request()->routeIs('admin.type-peoples.*'), $i['users']],
            ['Pessoas Trans', route('admin.trans-peoples.index'), request()->routeIs('admin.trans-peoples.*'), $i['user']],
            ['Entidades Parceiras', route('admin.partner-entities.index'), request()->routeIs('admin.partner-entities.*'), $i['heart']],
            ['Posts', route('admin.posts.index'), request()->routeIs('admin.posts.*'), $i['doc']],
            ['Categorias', route('admin.categories.index'), request()->routeIs('admin.categories.*'), $i['tag']],
            ['Comentários', route('admin.comments.index'), request()->routeIs('admin.comments.*'), $i['chat']],
            ['Páginas', route('admin.pages.index'), request()->routeIs('admin.pages.*'), $i['doc']],
            ['Páginas Estáticas', route('admin.static-pages.index'), request()->routeIs('admin.static-pages.*'), $i['doc']],
            ['Tipos de Link', route('admin.type-external-links.index'), request()->routeIs('admin.type-external-links.*'), $i['link']],
            ['Links Externos', route('admin.external-links.index'), request()->routeIs('admin.external-links.*'), $i['link']],
        ];
        if (auth()->user()?->hasRole('super-admin')) {
            $nav[] = ['Usuários', route('admin.users.index'), request()->routeIs('admin.users.*'), $i['users']];
            $nav[] = ['Modelos Excluídos', route('admin.deleted-models.index'), request()->routeIs('admin.deleted-models.*'), $i['trash']];
        }
    @endphp

    {{-- Sidebar fixa --}}
    <aside
        class="admin-aside fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full transform flex-col bg-slate-900 text-slate-300 transition-all duration-200 lg:translate-x-0"
        :class="sidebarOpen && 'translate-x-0'"
    >
        {{-- Logo + chevron (ambos sempre visíveis, lado a lado) --}}
        <div class="admin-logo-row flex h-16 shrink-0 items-center gap-2 px-3">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-extrabold tracking-tight text-white">
                <span class="admin-logo-full">Ca<span class="text-violet-400">NTI</span>n</span>
                <span class="admin-logo-mark">Ca<span class="text-violet-400">N</span></span>
            </a>
            <button type="button" @click="toggleCollapse()"
                    @mouseenter="showTip($el, collapsed ? 'Expandir menu' : 'Recolher menu')" @mouseleave="tip.show = false"
                    class="admin-chevron-toggle rounded-md p-1.5 text-slate-400 transition hover:bg-slate-800 hover:text-white"
                    aria-label="Recolher/expandir menu">
                <svg class="admin-chevron h-5 w-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            </button>
        </div>

        <nav class="scrollbar-dark flex-1 space-y-1 overflow-y-auto overflow-x-hidden px-3 py-2 text-sm">
            @foreach ($nav as [$label, $url, $active, $icon])
                <a href="{{ $url }}"
                   @mouseenter="showTip($el, @js($label))" @mouseleave="tip.show = false"
                   @class([
                        'admin-nav-item group flex items-center gap-3 rounded-lg px-3 py-2 transition',
                        'bg-violet-600 text-white' => $active,
                        'hover:bg-slate-800 hover:text-white' => ! $active,
                    ])
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    <span class="admin-label">{{ $label }}</span>
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- Tooltip flutuante (fixed): escapa do overflow do menu, fica acima do conteúdo --}}
    <div x-show="tip.show" x-cloak
         :style="`top: ${tip.y}px`"
         class="pointer-events-none fixed left-16 z-[60] ml-2 hidden -translate-y-1/2 whitespace-nowrap rounded-md bg-slate-900 px-2.5 py-1 text-xs font-medium text-white shadow-lg ring-1 ring-slate-700 lg:block"
         x-text="tip.text"></div>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

    {{-- Conteúdo --}}
    <div class="admin-content transition-all duration-200">
        <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-slate-200 bg-white px-4 lg:px-8">
            <button @click="sidebarOpen = ! sidebarOpen" class="rounded-md p-2 text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <h1 class="text-base font-semibold text-slate-700">{{ $title ?? 'Painel' }}</h1>

            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('site.home') }}" target="_blank" rel="noopener"
                   class="hidden items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 sm:flex">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    Ver o site
                </a>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = ! open" class="flex items-center gap-2 rounded-full py-1 pl-1 pr-3 hover:bg-slate-100">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-violet-600 text-sm font-semibold text-white">
                            {{ strtoupper(substr(auth()->user()->name ?? '?', 0, 1)) }}
                        </span>
                        <span class="hidden text-sm font-medium text-slate-700 sm:block">{{ auth()->user()->name ?? '' }}</span>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-0 top-12 z-50 w-48 rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">Sair</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 lg:p-8">
            @if (\Diglactic\Breadcrumbs\Breadcrumbs::exists())
                <nav aria-label="breadcrumb" class="mb-6 flex flex-wrap items-center gap-1.5 text-sm text-slate-500">
                    @foreach (\Diglactic\Breadcrumbs\Breadcrumbs::generate() as $crumb)
                        @if (! $loop->last && $crumb->url)
                            <a href="{{ $crumb->url }}" class="transition hover:text-violet-600">{{ $crumb->title }}</a>
                            <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                        @else
                            <span class="font-semibold text-slate-700" aria-current="page">{{ $crumb->title }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
