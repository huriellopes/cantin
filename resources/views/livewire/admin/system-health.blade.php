{{-- Polling próprio (10s) = saúde em tempo real, sem re-render do dashboard todo. --}}
<div wire:poll.10s class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    @php
        $healthStyles = [
            'ok' => ['bg-emerald-500', 'text-emerald-700', 'bg-emerald-50 border-emerald-100'],
            'warn' => ['bg-amber-500', 'text-amber-700', 'bg-amber-50 border-amber-100'],
            'down' => ['bg-rose-500', 'text-rose-700', 'bg-rose-50 border-rose-100'],
        ];
    @endphp
    <div class="mb-4 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-slate-600">{{ __('msg_dashboard.health_title') }}</h3>
        <span class="flex items-center gap-1.5 text-[11px] text-slate-400">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 motion-safe:animate-pulse"></span>
            {{ __('msg_dashboard.health_realtime') }}
        </span>
    </div>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($checks as $item)
            @php [$dot, $txt, $box] = $healthStyles[$item['status']] ?? $healthStyles['down']; @endphp
            <div class="flex items-center gap-3 rounded-xl border {{ $box }} px-4 py-3" wire:key="health-{{ $item['key'] }}">
                <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $dot }}"></span>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-700">{{ $item['label'] }}</p>
                    <p class="truncate text-xs {{ $txt }}">{{ $item['detail'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
