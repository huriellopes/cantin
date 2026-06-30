@props(['confirm' => []])

@if (! empty($confirm))
    <div class="fixed inset-0 z-[70] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50" wire:click="cancelConfirm"></div>
        <div class="relative z-10 w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="flex items-start gap-3">
                <span @class([
                    'flex h-10 w-10 shrink-0 items-center justify-center rounded-full',
                    'bg-rose-100 text-rose-600' => $confirm['danger'] ?? false,
                    'bg-violet-100 text-violet-600' => ! ($confirm['danger'] ?? false),
                ])>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">{{ $confirm['title'] ?? 'Confirmar' }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $confirm['message'] ?? '' }}</p>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" wire:click="cancelConfirm" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="button" wire:click="confirmed" wire:loading.attr="disabled" @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold text-white transition',
                    'bg-rose-600 hover:bg-rose-700' => $confirm['danger'] ?? false,
                    'bg-violet-600 hover:bg-violet-700' => ! ($confirm['danger'] ?? false),
                ])>
                    {{ $confirm['label'] ?? 'Confirmar' }}
                </button>
            </div>
        </div>
    </div>
@endif
