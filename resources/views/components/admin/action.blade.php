@props(['icon' => 'edit', 'color' => 'slate', 'label' => ''])

@php
    $icons = [
        'view' => 'M2.04 12.32a1 1 0 0 1 0-.64C3.42 7.51 7.36 4.5 12 4.5s8.57 3.01 9.96 7.18a1 1 0 0 1 0 .64C20.58 16.49 16.64 19.5 12 19.5s-8.57-3.01-9.96-7.18ZM15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z',
        'edit' => 'm16.86 4.49 2.65 2.65M3 21l3.9-.78a2 2 0 0 0 1.02-.55L20.7 6.79a1.87 1.87 0 0 0 0-2.65l-.84-.84a1.87 1.87 0 0 0-2.65 0L4.33 16.08a2 2 0 0 0-.55 1.02L3 21Z',
        'delete' => 'm14.74 9-.35 9m-4.78 0L9.26 9M19.2 5.79 18.16 19.6a2.25 2.25 0 0 1-2.24 2.15H8.08a2.25 2.25 0 0 1-2.24-2.15L4.8 5.79m9.96-.91a48 48 0 0 0-5.52 0m6.5.16-.66 9.95m-7.6 0L7 4.88M9.26 4.97v-.91A1.75 1.75 0 0 1 11 2.4h2a1.75 1.75 0 0 1 1.74 1.66v.91',
        'toggle' => 'M12 6v6m0 0a6 6 0 1 0 6 6M12 6a6 6 0 0 0-6 6',
        'reset' => 'M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.03 5.91l-3.72 3.72a2.25 2.25 0 0 1-1.59.66H7.5v1.5a.75.75 0 0 1-.75.75H5.25v1.5a.75.75 0 0 1-.75.75H3a.75.75 0 0 1-.75-.75v-1.94c0-.42.17-.82.46-1.12l5.4-5.4A6 6 0 1 1 21.75 8.25Z',
        'publish' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        'unpublish' => 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z',
        'restore' => 'M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3',
        'link' => 'M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25',
        'reply' => 'M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-1.5',
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

<button type="button" title="{{ $label }}" aria-label="{{ $label }}"
        {{ $attributes->merge(['class' => 'inline-flex h-8 w-8 items-center justify-center rounded-lg transition '.($colors[$color] ?? $colors['slate'])]) }}>
    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$icon] ?? $icons['edit'] }}"/>
    </svg>
</button>
