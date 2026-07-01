<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="shortcut icon" href="{{ asset('assets/images/cantin.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/CANTIn.png') }}">
    <title>{{ $title ?? 'Painel' }} · CaNTIn</title>
    {{-- Aplica o estado da sidebar antes da pintura (evita flicker no reload) --}}
    <script>
        if (localStorage.getItem('sidebarCollapsed') === '1') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>
    {{-- Editor rico Quill (usado nas páginas de criação/edição de posts e páginas). --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

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
        // Menu agrupado por seções. Cada seção: título + itens [label, url, ativo, ícone].
        $sections = [
            [__('admin.nav_group.general'), [
                [__('admin.nav.dashboard'), route('admin.dashboard'), request()->routeIs('admin.dashboard'), 'layout-dashboard'],
            ]],
            [__('admin.nav_group.community'), [
                [__('admin.nav.terreiros'), route('admin.terreiros.index'), request()->routeIs('admin.terreiros.*'), 'house'],
                [__('admin.nav.nations'), route('admin.nations.index'), request()->routeIs('admin.nations.*'), 'globe'],
                [__('admin.nav.type_terreiros'), route('admin.type-terreiros.index'), request()->routeIs('admin.type-terreiros.*'), 'layout-grid'],
                [__('admin.nav.genders'), route('admin.type-peoples.index'), request()->routeIs('admin.type-peoples.*'), 'venus-and-mars'],
                [__('admin.nav.trans_people'), route('admin.trans-peoples.index'), request()->routeIs('admin.trans-peoples.*'), 'user'],
                [__('admin.nav.partners'), route('admin.partner-entities.index'), request()->routeIs('admin.partner-entities.*'), 'heart-handshake'],
            ]],
            [__('admin.nav_group.blog'), [
                [__('admin.nav.posts'), route('admin.posts.index'), request()->routeIs('admin.posts.*'), 'newspaper'],
                [__('admin.nav.categories'), route('admin.categories.index'), request()->routeIs('admin.categories.*'), 'tag'],
                [__('admin.nav.comments'), route('admin.comments.index'), request()->routeIs('admin.comments.*'), 'message-square'],
            ]],
            [__('admin.nav_group.pages_links'), [
                [__('admin.nav.pages'), route('admin.pages.index'), request()->routeIs('admin.pages.*'), 'file'],
                [__('admin.nav.static_pages'), route('admin.static-pages.index'), request()->routeIs('admin.static-pages.*'), 'file-text'],
                [__('admin.nav.link_types'), route('admin.type-external-links.index'), request()->routeIs('admin.type-external-links.*'), 'link'],
                [__('admin.nav.external_links'), route('admin.external-links.index'), request()->routeIs('admin.external-links.*'), 'external-link'],
            ]],
        ];
        if (auth()->user()?->isSuperAdmin()) {
            $sections[] = [__('admin.nav_group.system'), [
                [__('admin.nav.users'), route('admin.users.index'), request()->routeIs('admin.users.*'), 'users'],
                [__('admin.nav.deleted_models'), route('admin.deleted-models.index'), request()->routeIs('admin.deleted-models.*'), 'trash-2'],
                [__('admin.nav.impersonation_logs'), route('admin.impersonation-logs.index'), request()->routeIs('admin.impersonation-logs.*'), 'venetian-mask'],
                [__('admin.nav.system'), route('admin.system.index'), request()->routeIs('admin.system.*'), 'activity'],
            ]];
        }
    @endphp

    {{-- Sidebar fixa --}}
    <aside
        class="admin-aside fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full transform flex-col bg-slate-900 text-slate-300 transition-all duration-200 lg:translate-x-0"
        :class="sidebarOpen && 'translate-x-0'"
    >
        {{-- Logo + chevron (ambos sempre visíveis, lado a lado) --}}
        <div class="admin-logo-row flex h-16 shrink-0 items-center gap-2 px-3">
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center">
                <img src="{{ asset('assets/images/CANTIn.png') }}" alt="CaNTIn" class="admin-logo-full" />
                <img src="{{ asset('assets/images/CANTIn.png') }}" alt="CaNTIn" class="admin-logo-mark" />
            </a>
            <button type="button" @click="toggleCollapse()"
                    @mouseenter="showTip($el, collapsed ? @js(__('admin.header.expand')) : @js(__('admin.header.collapse')))" @mouseleave="tip.show = false"
                    class="admin-chevron-toggle rounded-md p-1.5 text-slate-400 transition hover:bg-slate-800 hover:text-white"
                    aria-label="Recolher/expandir menu">
                @svg('lucide-chevron-left', 'admin-chevron h-5 w-5 transition-transform')
            </button>
        </div>

        <nav class="scrollbar-dark flex-1 space-y-1 overflow-y-auto overflow-x-hidden px-3 py-2 text-sm">
            @foreach ($sections as [$sectionTitle, $items])
                <div class="admin-nav-group">
                    <p class="admin-group-label">{{ $sectionTitle }}</p>
                    @foreach ($items as [$label, $url, $active, $icon])
                        <a href="{{ $url }}" wire:navigate
                           @mouseenter="showTip($el, @js($label))" @mouseleave="tip.show = false"
                           @class([
                                'admin-nav-item group flex items-center gap-3 rounded-lg px-3 py-2 transition',
                                'bg-violet-600 text-white' => $active,
                                'hover:bg-slate-800 hover:text-white' => ! $active,
                            ])
                        >
                            @svg('lucide-'.$icon, 'h-5 w-5 shrink-0')
                            <span class="admin-label">{{ $label }}</span>
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>

        {{-- Footer fixo: versão atual do projeto (auto-atualizada no deploy) --}}
        <div class="shrink-0 border-t border-slate-800 px-3 py-3 text-center text-xs text-slate-500">
            <span class="admin-label">CaNTIn </span>v{{ \App\Support\Version::current() }}
        </div>
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
                @svg('lucide-menu', 'h-6 w-6')
            </button>

            <h1 class="text-base font-semibold text-slate-700">{{ $title ?? __('admin.header.panel') }}</h1>

            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('site.home') }}" target="_blank" rel="noopener"
                   class="hidden items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 sm:flex">
                    @svg('lucide-external-link', 'h-4 w-4')
                    {{ __('admin.header.view_site') }}
                </a>

                <livewire:admin.support.exports-manager />

                <x-lang-switcher />

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = ! open" class="flex items-center gap-2 rounded-full py-1 pl-1 pr-3 hover:bg-slate-100">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-violet-600 text-sm font-semibold text-white">
                            {{ strtoupper(substr(auth()->user()->name ?? '?', 0, 1)) }}
                        </span>
                        <span class="hidden text-sm font-medium text-slate-700 sm:block">{{ auth()->user()->name ?? '' }}</span>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-0 top-12 z-50 w-48 rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                        <a href="{{ route('admin.profile') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                            @svg('lucide-user-cog', 'h-4 w-4')
                            {{ __('admin.header.my_profile') }}
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">
                                @svg('lucide-log-out', 'h-4 w-4')
                                {{ __('admin.header.logout') }}
                            </button>
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
                            <a href="{{ $crumb->url }}" wire:navigate class="transition hover:text-violet-600">{{ $crumb->title }}</a>
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

{{-- Toaster --}}
<div
    x-data="{ toasts: [] }"
    @toast.window="
        const id = Date.now() + Math.random();
        toasts.push({ id, type: ($event.detail.type || 'success'), message: $event.detail.message });
        setTimeout(() => toasts = toasts.filter(t => t.id !== id), 4000)
    "
    class="fixed bottom-4 right-4 z-[80] flex w-80 max-w-[calc(100vw-2rem)] flex-col gap-2"
>
    <template x-for="t in toasts" :key="t.id">
        <div x-transition.opacity.duration.300ms
             class="flex items-start gap-3 rounded-xl border bg-white p-4 shadow-lg"
             :class="{ 'border-emerald-200': t.type === 'success', 'border-rose-200': t.type === 'error', 'border-amber-200': t.type === 'warning', 'border-sky-200': t.type === 'info' }">
            <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full"
                  :class="{ 'bg-emerald-500': t.type === 'success', 'bg-rose-500': t.type === 'error', 'bg-amber-500': t.type === 'warning', 'bg-sky-500': t.type === 'info' }"></span>
            <p class="flex-1 text-sm text-slate-700" x-text="t.message"></p>
            <button @click="toasts = toasts.filter(x => x.id !== t.id)" class="text-slate-400 hover:text-slate-600">
                @svg('lucide-x', 'h-4 w-4')
            </button>
        </div>
    </template>
</div>

<x-impersonation-banner />

{{-- Toast vindo de um redirect (flash de sessão) — ex.: após salvar em página dedicada. --}}
@if (session()->has('toast'))
    <script>
        setTimeout(function () {
            window.dispatchEvent(new CustomEvent('toast', { detail: @js(session('toast')) }));
        }, 200);
    </script>
@endif

@livewireScripts
</body>
</html>
