<div class="mx-auto max-w-6xl px-6 py-16">
    <header class="text-center">
        <h1 class="text-3xl font-extrabold text-slate-800 sm:text-4xl">{{ __('page_terreiros_search.title') }}</h1>
        <p class="mt-2 text-slate-500">{{ __('page_terreiros_search.subtitle') }}</p>
    </header>

    <div class="mx-auto mt-8 max-w-xl">
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('page_terreiros_search.search_placeholder') }}"
               class="w-full rounded-full border border-slate-300 px-5 py-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
    </div>

    <div wire:loading wire:target="search" class="mt-6 text-center text-sm text-slate-400">{{ __('page_terreiros_search.searching') }}</div>

    <div class="mt-8 space-y-4" wire:loading.remove wire:target="search" x-data="{ expanded: null }">
        @forelse ($terreiros as $terreiro)
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md" wire:key="t-{{ $terreiro->id }}">
                <div class="flex flex-wrap items-center justify-between gap-4 p-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $terreiro->name }}</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $terreiro->nation?->name }} · {{ $terreiro->address?->city?->name }}/{{ $terreiro->address?->state?->abbr }}
                        </p>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-slate-600">
                        <span class="hidden sm:inline">{{ maskPhone($terreiro->phone) }}</span>
                        <button @click="expanded = (expanded === {{ $terreiro->id }} ? null : {{ $terreiro->id }})"
                                class="rounded-full border border-violet-200 px-4 py-1.5 text-sm font-medium text-violet-700 transition hover:bg-violet-50">
                            {{ __('page_terreiros_search.details') }}
                        </button>
                    </div>
                </div>
                <div x-show="expanded === {{ $terreiro->id }}" x-transition x-cloak class="border-t border-slate-100 bg-slate-50 p-5 text-sm text-slate-600">
                    <dl class="grid gap-2 sm:grid-cols-2">
                        <div><dt class="font-semibold text-slate-700">{{ __('page_terreiros_search.leadership') }}</dt><dd>{{ $terreiro->leadership_orunko }}</dd></div>
                        <div><dt class="font-semibold text-slate-700">{{ __('page_terreiros_search.phone') }}</dt><dd>{{ maskPhone($terreiro->phone) }}</dd></div>
                        <div class="sm:col-span-2"><dt class="font-semibold text-slate-700">{{ __('page_terreiros_search.address') }}</dt>
                            <dd>{{ $terreiro->address?->address }}, {{ $terreiro->address?->neighborhood }}@if(!empty($terreiro->address?->complement)), {{ $terreiro->address->complement }}@endif — {{ $terreiro->address?->city?->name }}/{{ $terreiro->address?->state?->abbr }}, {{ $terreiro->address?->zipcode }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 p-12 text-center text-slate-400">{{ __('page_terreiros_search.empty') }}</div>
        @endforelse
    </div>

    <div class="mt-8">{{ $terreiros->links() }}</div>
</div>
