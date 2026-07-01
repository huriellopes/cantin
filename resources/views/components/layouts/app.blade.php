<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Huriel Lopes">
    <meta name="google-site-verification" content="msIw9NHp2yJdz3pV1FCJKyYoPmTu-vXAe9fmgk6cCAQ" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="shortcut icon" href="{{ asset('assets/images/cantin.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/CANTIn.png') }}">

    {{-- Preload da imagem de fundo do hero (LCP) — só na home, onde ela aparece. --}}
    @if (request()->routeIs('site.home'))
        <link rel="preload" as="image" href="{{ asset('assets/images/new/background-outro.png') }}" fetchpriority="high">
    @endif

    {{-- Título, description, canonical, OpenGraph e Twitter (archtechx/laravel-seo).
         Os valores padrão vêm do AppServiceProvider; cada página os sobrescreve. --}}
    <x-seo::meta />

    {{-- Dados estruturados JSON-LD (schema.org): Organization global + extras por página. --}}
    {!! $organizationJsonLd ?? '' !!}
    @stack('jsonld')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    {!! ToastMagic::styles() !!}
</head>
<body class="min-h-screen bg-white text-slate-800 antialiased">
    @php $transparentNav = request()->routeIs('site.home'); @endphp
    <nav x-data="{ open: false, scrolled: false, get solid() { return {{ $transparentNav ? 'false' : 'true' }} || this.scrolled || this.open } }"
         @scroll.window="scrolled = window.scrollY > 10"
         class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
         :class="solid ? 'bg-white/95 shadow-sm backdrop-blur' : 'bg-transparent'">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="{{ route('site.home') }}" wire:navigate class="flex items-center">
                <img src="{{ asset('assets/images/CANTIn.png') }}" alt="CaNTIn" class="h-11 w-auto" />
            </a>

            @php
                $links = [
                    ['site.home', __('nav.home')],
                    ['site.about', __('nav.about')],
                    ['site.terreiros.search', __('nav.terreiros')],
                    ['site.partners-entities', __('nav.partners')],
                    ['site.trans-people', __('nav.trans_people')],
                    ['site.blog.posts', __('nav.blog')],
                    ['site.links.external', __('nav.links')],
                ];
            @endphp

            <div class="hidden items-center gap-6 lg:flex">
                @foreach ($links as [$route, $label])
                    <a href="{{ route($route) }}" wire:navigate
                       class="text-sm font-medium transition hover:opacity-70 {{ request()->routeIs($route) ? 'text-violet-600' : '' }}"
                       :class="solid ? '{{ request()->routeIs($route) ? 'text-violet-600' : 'text-slate-700' }}' : 'text-white drop-shadow'">
                        {{ $label }}
                    </a>
                @endforeach
                @auth
                    @if (auth()->user()->hasRole('admin', 'super-admin'))
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-violet-600 hover:opacity-70">{{ __('nav.panel') }}</a>
                    @endif
                @else
                    <a href="{{ route('site.auth.login') }}" wire:navigate
                       class="text-sm font-medium transition hover:opacity-70"
                       :class="solid ? 'text-slate-700' : 'text-white drop-shadow'">{{ __('nav.login') }}</a>
                @endauth
                <a href="{{ route('site.terreiros.create') }}" wire:navigate
                   class="rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:shadow-lg hover:brightness-110">
                    {{ __('nav.register_terreiro') }}
                </a>
                <span :class="solid ? 'text-slate-700' : 'text-white drop-shadow'">
                    <x-lang-switcher />
                </span>
            </div>

            <button @click="open = !open" class="lg:hidden" aria-label="Menu"
                    :class="solid ? 'text-slate-800' : 'text-white'">
                <span x-show="!open">@svg('lucide-menu', 'h-7 w-7')</span>
                <span x-show="open" x-cloak>@svg('lucide-x', 'h-7 w-7')</span>
            </button>
        </div>

        {{-- Menu mobile --}}
        <div x-show="open" x-cloak x-transition class="border-t border-slate-100 bg-white px-4 py-4 lg:hidden">
            <div class="flex flex-col gap-1">
                @foreach ($links as [$route, $label])
                    <a href="{{ route($route) }}" wire:navigate class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs($route) ? 'bg-violet-50 text-violet-700' : 'text-slate-700 hover:bg-slate-50' }}">{{ $label }}</a>
                @endforeach
                @auth
                    @if (auth()->user()->hasRole('admin', 'super-admin'))
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="rounded-lg px-3 py-2 text-sm font-medium text-violet-700 hover:bg-violet-50">{{ __('nav.panel') }}</a>
                    @endif
                @else
                    <a href="{{ route('site.auth.login') }}" wire:navigate class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">{{ __('nav.login') }}</a>
                @endauth
                <a href="{{ route('site.terreiros.create') }}" wire:navigate class="mt-2 rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-5 py-2 text-center text-sm font-semibold text-white">{{ __('nav.register_terreiro') }}</a>
                <div class="mt-2 border-t border-slate-100 pt-2">
                    <x-lang-switcher align="left" />
                </div>
            </div>
        </div>
    </nav>

    <main class="{{ request()->routeIs('site.home') ? '' : 'pt-16' }}">
        {{ $slot }}
    </main>

    <livewire:site.components.whatsapp-button />

    <livewire:site.components.footer />

    {{-- Banner de cookies + Analytics/Ads carregados apenas com consentimento --}}
    <x-cookie-consent />
    <x-impersonation-banner />

    @livewireScripts
    {!! ToastMagic::scripts() !!}
</body>
</html>
