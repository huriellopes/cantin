@props(['color' => 'slate'])

@php
    $map = [
        'success' => 'bg-emerald-100 text-emerald-700',
        'danger' => 'bg-rose-100 text-rose-700',
        'warning' => 'bg-amber-100 text-amber-700',
        'primary' => 'bg-violet-100 text-violet-700',
        'slate' => 'bg-slate-100 text-slate-600',
    ];
@endphp

<span {{ $attributes->class(['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', $map[$color] ?? $map['slate']]) }}>
    {{ $slot }}
</span>
