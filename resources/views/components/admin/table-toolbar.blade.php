@props([
    'options' => [],
    'searchModel' => 'search',
    'perPageModel' => 'perPage',
    'placeholder' => null,
])

<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    {{-- Busca --}}
    <div class="relative w-full sm:max-w-xs">
        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
            @svg('lucide-search', 'h-4 w-4')
        </span>
        <input type="search" wire:model.live.debounce.400ms="{{ $searchModel }}"
               placeholder="{{ $placeholder ?? __('common.search') }}"
               class="block w-full rounded-lg border border-slate-300 py-2 pl-9 pr-3 text-sm focus:border-violet-500 focus:ring-violet-500">
    </div>

    {{-- Itens por página --}}
    <label class="flex items-center gap-2 text-sm text-slate-500">
        <span class="hidden sm:inline">{{ __('common.per_page') }}</span>
        <select wire:model.live="{{ $perPageModel }}"
                class="rounded-lg border border-slate-300 py-2 pl-3 pr-8 text-sm focus:border-violet-500 focus:ring-violet-500">
            @foreach ($options as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    </label>
</div>
