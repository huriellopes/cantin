@props(['icon' => 'edit', 'color' => 'slate', 'label' => ''])

@php
    $lucide = [
        'view' => 'eye',
        'edit' => 'pencil',
        'delete' => 'trash-2',
        'toggle' => 'power',
        'reset' => 'key-round',
        'publish' => 'circle-check',
        'unpublish' => 'circle-x',
        'restore' => 'rotate-ccw',
        'link' => 'external-link',
        'reply' => 'reply',
    ];
    $colors = [
        'slate' => 'text-slate-500 hover:bg-slate-100',
        'violet' => 'text-violet-600 hover:bg-violet-50',
        'rose' => 'text-rose-600 hover:bg-rose-50',
        'emerald' => 'text-emerald-600 hover:bg-emerald-50',
        'amber' => 'text-amber-600 hover:bg-amber-50',
        'sky' => 'text-sky-600 hover:bg-sky-50',
    ];
@endphp

{{-- Botão de ação (somente ícone) com tooltip no hover (desktop), à esquerda
     para não ser cortado pelo overflow horizontal da tabela. --}}
<x-admin.tooltip :label="$label" position="left">
    <button type="button" aria-label="{{ $label }}"
            {{ $attributes->merge(['class' => 'inline-flex h-8 w-8 items-center justify-center rounded-lg transition '.($colors[$color] ?? $colors['slate'])]) }}>
        @svg('lucide-'.($lucide[$icon] ?? 'pencil'), 'h-[18px] w-[18px]')
    </button>
</x-admin.tooltip>
