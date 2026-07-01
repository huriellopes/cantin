<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="shortcut icon" href="{{ asset('assets/images/cantin.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/CANTIn.png') }}">
    <title>{{ $title ?? 'CaNTIn' }} · CaNTIn</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex min-h-full flex-col bg-slate-100 text-slate-800 antialiased">
    <main class="flex flex-1 items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="mb-8 flex justify-center">
                <img src="{{ asset('assets/images/cantin-logo.webp') }}" alt="CaNTIn" width="160" height="160" class="h-12 w-auto" />
            </div>

            {{ $slot }}
        </div>
    </main>

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

    {{-- Toast vindo de um redirect (flash de sessão). --}}
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
