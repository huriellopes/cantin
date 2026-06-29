<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Painel' }} · CaNTIn</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body class="h-full bg-slate-100 text-slate-800 antialiased">
<div x-data="{ sidebarOpen: false }" class="min-h-full">
    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-40 w-64 transform bg-slate-900 text-slate-300 transition-transform duration-200 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="flex h-16 items-center gap-2 px-6 text-white">
            <span class="text-xl font-bold tracking-tight">Ca<span class="text-violet-400">NTI</span>n</span>
            <span class="ml-auto rounded bg-slate-800 px-2 py-0.5 text-[10px] uppercase tracking-wider text-slate-400">Admin</span>
        </div>

        <nav class="mt-4 space-y-1 px-3 text-sm">
            @php
                $nav = [
                    ['Painel', route('admin.dashboard'), request()->routeIs('admin.dashboard')],
                    ['Terreiros', '#', false],
                    ['Pessoas Trans', '#', false],
                    ['Entidades Parceiras', '#', false],
                    ['Posts', '#', false],
                    ['Comentários', '#', false],
                    ['Páginas', '#', false],
                ];

                if (auth()->user()?->hasRole('super-admin')) {
                    $nav[] = ['Usuários', route('admin.users.index'), request()->routeIs('admin.users.*')];
                }
            @endphp
            @foreach ($nav as [$label, $url, $active])
                <a href="{{ $url }}" @class([
                    'flex items-center gap-3 rounded-lg px-3 py-2 transition',
                    'bg-violet-600 text-white' => $active,
                    'hover:bg-slate-800 hover:text-white' => ! $active,
                ])>
                    <span class="h-1.5 w-1.5 rounded-full {{ $active ? 'bg-white' : 'bg-slate-600' }}"></span>
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="absolute bottom-0 w-full p-4">
            <a href="{{ route('site.home') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-400 hover:bg-slate-800 hover:text-white">
                ← Ver o site
            </a>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

    {{-- Conteúdo --}}
    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-slate-200 bg-white px-4 lg:px-8">
            <button @click="sidebarOpen = !sidebarOpen" class="rounded-md p-2 text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <h1 class="text-base font-semibold text-slate-700">{{ $title ?? 'Painel' }}</h1>

            <div class="ml-auto flex items-center gap-4" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 rounded-full py-1 pl-1 pr-3 hover:bg-slate-100">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-violet-600 text-sm font-semibold text-white">
                        {{ strtoupper(substr(auth()->user()->name ?? '?', 0, 1)) }}
                    </span>
                    <span class="hidden text-sm font-medium text-slate-700 sm:block">{{ auth()->user()->name ?? '' }}</span>
                </button>
                <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-4 top-14 w-48 rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">Sair</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="p-4 lg:p-8">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
