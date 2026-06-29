<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Huriel Lopes">
    <meta name="description" content="CaNTIn - Cadastro Nacional de Terreiros Inclusivos" />
    <meta name="google-site-verification" content="YE-utvBYDHJCzV1g7jBT6a79BatD-F31NOT849JDLyM" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('/assets/images/cantin.ico') }}" type="image/x-icon">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    {!! ToastMagic::styles() !!}
</head>
<body class="min-h-screen bg-white text-slate-800 antialiased">
    <nav x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 10"
         class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
         :class="scrolled || open ? 'bg-white/95 shadow-sm backdrop-blur' : 'bg-transparent'">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="{{ route('site.home') }}" wire:navigate class="text-2xl font-extrabold tracking-tight"
               :class="scrolled || open ? 'text-slate-900' : 'text-white drop-shadow'">
                Ca<span class="bg-gradient-to-r from-violet-500 to-pink-500 bg-clip-text text-transparent">NTI</span>n
            </a>

            @php
                $links = [
                    ['site.home', 'Início'],
                    ['site.about', 'Sobre'],
                    ['site.terreiros.search', 'Terreiros'],
                    ['site.partners-entities', 'Entidades'],
                    ['site.trans-people', 'Pessoas Trans'],
                    ['site.blog.posts', 'Blog'],
                    ['site.links.external', 'Links'],
                ];
            @endphp

            <div class="hidden items-center gap-6 lg:flex">
                @foreach ($links as [$route, $label])
                    <a href="{{ route($route) }}" wire:navigate
                       class="text-sm font-medium transition hover:opacity-70 {{ request()->routeIs($route) ? 'text-violet-600' : '' }}"
                       :class="(scrolled || open) ? '{{ request()->routeIs($route) ? 'text-violet-600' : 'text-slate-700' }}' : 'text-white drop-shadow'">
                        {{ $label }}
                    </a>
                @endforeach
                @auth
                    @if (auth()->user()->hasRole('admin', 'super-admin'))
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-violet-600 hover:opacity-70">Painel</a>
                    @endif
                @endauth
                <a href="{{ route('site.terreiros.create') }}" wire:navigate
                   class="rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:shadow-lg hover:brightness-110">
                    Cadastrar terreiro
                </a>
            </div>

            <button @click="open = !open" class="lg:hidden" aria-label="Menu"
                    :class="scrolled || open ? 'text-slate-800' : 'text-white'">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path x-show="!open" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/><path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Menu mobile --}}
        <div x-show="open" x-cloak x-transition class="border-t border-slate-100 bg-white px-4 py-4 lg:hidden">
            <div class="flex flex-col gap-1">
                @foreach ($links as [$route, $label])
                    <a href="{{ route($route) }}" wire:navigate class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs($route) ? 'bg-violet-50 text-violet-700' : 'text-slate-700 hover:bg-slate-50' }}">{{ $label }}</a>
                @endforeach
                <a href="{{ route('site.terreiros.create') }}" wire:navigate class="mt-2 rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-5 py-2 text-center text-sm font-semibold text-white">Cadastrar terreiro</a>
            </div>
        </div>
    </nav>

    {{ $slot }}

    <livewire:site.components.whatsapp-button />

    @if (! request()->routeIs('site.auth.login') && ! request()->routeIs('site.terreiros.search'))
        <livewire:site.components.footer />
    @endif

    {{-- Google Analytics --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4VSY21XL8V"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-4VSY21XL8V');
    </script>

    @livewireScripts
    {!! ToastMagic::scripts() !!}
</body>
</html>
