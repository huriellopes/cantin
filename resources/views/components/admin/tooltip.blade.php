@props([
    'label' => '',
    'position' => 'left',
])

{{--
    Tooltip reutilizável para elementos (tipicamente botões só-ícone).
    Aparece no hover apenas no desktop (sm+); no mobile fica oculto (sem hover).
    Uso:
        <x-admin.tooltip :label="__('common.edit')">
            <button ...>@svg('lucide-pencil')</button>
        </x-admin.tooltip>
--}}
@php
    $positions = [
        'top' => 'bottom-full left-1/2 mb-2 -translate-x-1/2',
        'bottom' => 'top-full left-1/2 mt-2 -translate-x-1/2',
        'left' => 'right-full top-1/2 mr-2 -translate-y-1/2',
        'right' => 'left-full top-1/2 ml-2 -translate-y-1/2',
    ];
    $place = $positions[$position] ?? $positions['left'];
@endphp

<span class="group/tt relative inline-flex">
    {{ $slot }}
    @if ($label !== '')
        <span role="tooltip"
              class="pointer-events-none absolute z-50 hidden whitespace-nowrap rounded-md bg-slate-900 px-2 py-1 text-xs font-medium text-white opacity-0 shadow-lg ring-1 ring-slate-700 transition-opacity duration-150 sm:block sm:group-hover/tt:opacity-100 {{ $place }}">
            {{ $label }}
        </span>
    @endif
</span>
