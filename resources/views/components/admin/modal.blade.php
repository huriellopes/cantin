@props(['show' => 'showModal', 'title' => ''])

<div
    x-data
    x-show="$wire.{{ $show }}"
    x-cloak
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    <div class="absolute inset-0 bg-slate-900/50" @click="$wire.{{ $show }} = false"></div>

    <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-800">{{ $title }}</h3>
            <button type="button" @click="$wire.{{ $show }} = false" class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="max-h-[70vh] overflow-y-auto px-6 py-5">
            {{ $slot }}
        </div>
    </div>
</div>
