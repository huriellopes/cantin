@props(['show' => false, 'title' => 'Detalhes', 'data' => []])

@if ($show)
    <div class="fixed inset-0 z-[65] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50" wire:click="$set('showView', false)"></div>
        <div class="relative z-10 w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-lg font-semibold text-slate-800">{{ $title }}</h3>
                <button type="button" wire:click="$set('showView', false)" class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <dl class="max-h-[70vh] divide-y divide-slate-100 overflow-y-auto px-6 py-2">
                @foreach ($data as $row)
                    <div class="py-3">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $row['label'] }}</dt>
                        <dd class="mt-1 text-sm text-slate-700">{{ filled($row['value']) ? $row['value'] : '—' }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
@endif
