@props(['align' => 'right'])

@php
    $locales = config('app.available_locales', []);
    $current = app()->getLocale();
    $labels = ['pt_BR' => 'PT', 'en' => 'EN'];
@endphp

<div class="relative" x-data="{ open: false }" @keydown.escape="open = false">
    <button type="button" @click="open = ! open"
            {{ $attributes->class(['flex items-center gap-1 rounded-lg px-2 py-1.5 text-sm font-medium transition-opacity hover:opacity-70']) }}
            aria-label="{{ __('nav.language') }}">
        @svg('lucide-languages', 'h-4 w-4')
        <span>{{ $labels[$current] ?? strtoupper($current) }}</span>
        @svg('lucide-chevron-down', 'h-3.5 w-3.5')
    </button>

    <div x-show="open" x-cloak @click.outside="open = false" x-transition.opacity
         class="absolute {{ $align === 'right' ? 'right-0' : 'left-0' }} top-10 z-50 w-36 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 text-slate-700 shadow-lg">
        @foreach ($locales as $code => $name)
            <a href="{{ route('locale.switch', $code) }}" wire:navigate
               class="flex items-center justify-between px-3 py-2 text-sm hover:bg-slate-50 {{ $code === $current ? 'font-semibold text-violet-600' : '' }}">
                {{ $name }}
                @if ($code === $current)
                    @svg('lucide-check', 'h-4 w-4')
                @endif
            </a>
        @endforeach
    </div>
</div>
