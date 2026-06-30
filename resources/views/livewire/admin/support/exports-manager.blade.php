<div wire:poll.5s="check" x-data="{ open: false }" class="relative">
    <button type="button" @click="open = ! open"
            class="relative inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100"
            title="{{ __('exports.title') }}" aria-label="{{ __('exports.title') }}">
        @svg('lucide-download', 'h-5 w-5')
        @if ($exports->isNotEmpty())
            <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-violet-600 px-1 text-[10px] font-semibold text-white">{{ $exports->count() }}</span>
        @endif
    </button>

    <div x-show="open" x-cloak @click.outside="open = false" x-transition.opacity
         class="absolute right-0 top-11 z-50 w-72 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
        <div class="border-b border-slate-100 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-500">
            {{ __('exports.title') }}
        </div>
        @forelse ($exports as $export)
            <a href="{{ route('admin.exports.download', $export->id) }}"
               class="flex items-center justify-between gap-2 px-4 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50">
                <span class="truncate">{{ $export->name }}</span>
                @svg('lucide-download', 'h-4 w-4 shrink-0 text-violet-600')
            </a>
        @empty
            <p class="px-4 py-4 text-center text-sm text-slate-400">{{ __('exports.empty') }}</p>
        @endforelse
    </div>
</div>
