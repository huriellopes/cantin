@props([
    'column' => null,
    'sortField' => null,
    'sortDirection' => 'desc',
    'align' => 'left',
])

@php
    $sortable = ! is_null($column);
    $active = $sortable && $sortField === $column;
@endphp

<th {{ $attributes->class(['px-4 py-3', 'text-right' => $align === 'right']) }}>
    @if ($sortable)
        <button type="button" wire:click="sortBy('{{ $column }}')"
                class="group inline-flex items-center gap-1 font-semibold uppercase tracking-wide transition hover:text-slate-700 {{ $align === 'right' ? 'flex-row-reverse' : '' }}">
            <span>{{ $slot }}</span>
            @if ($active)
                @svg('lucide-'.($sortDirection === 'asc' ? 'arrow-up' : 'arrow-down'), 'h-3.5 w-3.5 text-violet-600')
            @else
                @svg('lucide-chevrons-up-down', 'h-3.5 w-3.5 text-slate-300 group-hover:text-slate-400')
            @endif
        </button>
    @else
        {{ $slot }}
    @endif
</th>
