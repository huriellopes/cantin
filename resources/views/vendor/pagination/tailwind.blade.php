@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500">
            {{ __('Showing') }}
            <span class="font-medium text-slate-700">{{ $paginator->firstItem() ?? 0 }}</span>
            {{ __('to') }}
            <span class="font-medium text-slate-700">{{ $paginator->lastItem() ?? 0 }}</span>
            {{ __('of') }}
            <span class="font-medium text-slate-700">{{ $paginator->total() }}</span>
            {{ __('results') }}
        </p>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="inline-flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-lg border border-slate-200 text-slate-300">
                    @svg('lucide-chevron-left', 'h-4 w-4')
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                    @svg('lucide-chevron-left', 'h-4 w-4')
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="hidden h-9 min-w-9 items-center justify-center px-2 text-sm text-slate-400 sm:inline-flex">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg bg-violet-600 px-3 text-sm font-semibold text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="hidden h-9 min-w-9 items-center justify-center rounded-lg border border-slate-200 px-3 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 sm:inline-flex">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                    @svg('lucide-chevron-right', 'h-4 w-4')
                </a>
            @else
                <span aria-disabled="true" class="inline-flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-lg border border-slate-200 text-slate-300">
                    @svg('lucide-chevron-right', 'h-4 w-4')
                </span>
            @endif
        </div>
    </nav>
@endif
