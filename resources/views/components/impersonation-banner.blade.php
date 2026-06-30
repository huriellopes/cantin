@if (session('impersonator_id') && auth()->check())
    <div class="fixed inset-x-0 bottom-4 z-[80] flex justify-center px-4">
        <div class="flex items-center gap-4 rounded-full border border-amber-300 bg-amber-50/95 px-5 py-2.5 shadow-xl backdrop-blur">
            <span class="flex items-center gap-2 text-sm text-amber-800">
                @svg('lucide-venetian-mask', 'h-5 w-5 shrink-0')
                {{ __('common.impersonating', ['name' => auth()->user()->name]) }}
            </span>
            <form method="POST" action="{{ route('impersonate.leave') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-full bg-amber-600 px-4 py-1.5 text-sm font-semibold text-white transition hover:bg-amber-700">
                    @svg('lucide-log-out', 'h-4 w-4')
                    {{ __('common.stop_impersonating') }}
                </button>
            </form>
        </div>
    </div>
@endif
