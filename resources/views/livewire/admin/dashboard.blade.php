<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">{{ __('page_admin_dashboard.greeting', ['name' => auth()->user()->name]) }}</h2>
        <p class="mt-1 text-sm text-slate-500">{{ __('page_admin_dashboard.summary_subtitle') }}</p>
    </div>

    {{-- Stat cards --}}
    @php
        $palette = [
            'sky' => 'bg-sky-100 text-sky-600',
            'violet' => 'bg-violet-100 text-violet-600',
            'amber' => 'bg-amber-100 text-amber-600',
            'emerald' => 'bg-emerald-100 text-emerald-600',
            'rose' => 'bg-rose-100 text-rose-600',
            'indigo' => 'bg-indigo-100 text-indigo-600',
        ];
    @endphp
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($stats as $stat)
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $palette[$stat['color']] ?? 'bg-slate-100 text-slate-600' }}">
                    @svg('lucide-'.$stat['icon'], 'h-6 w-6')
                </div>
                <div>
                    <p class="text-sm text-slate-500">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($stat['value'], 0, ',', '.') }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Gráficos por período --}}
    @php
        $barColors = ['sky' => 'bg-sky-500', 'violet' => 'bg-violet-500', 'amber' => 'bg-amber-500'];
    @endphp

    {{-- Filtro de período --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="text-sm font-semibold text-slate-600">{{ __('msg_dashboard.charts_heading') }}</h3>
            @isset($updatedAt)
                <p class="text-[11px] text-slate-400">{{ __('msg_dashboard.updated_at', ['time' => $updatedAt->translatedFormat('d/m/Y H:i')]) }}</p>
            @endisset
        </div>
        <div class="inline-flex rounded-lg border border-slate-200 bg-white p-1 shadow-sm" role="group">
            @foreach ($periodOptions as $days => $label)
                <button type="button" wire:click="setPeriod({{ $days }})"
                        class="rounded-md px-3 py-1.5 text-xs font-semibold transition {{ $period === $days ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        @foreach ($charts as $chart)
            @php
                $total = $chart['series']->sum('value');
                $max = max(1, $chart['series']->max('value'));
            @endphp
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-baseline justify-between">
                    <h3 class="text-sm font-semibold text-slate-700">{{ $chart['title'] }}</h3>
                    <span class="text-xs text-slate-400">{{ __('page_admin_dashboard.chart_total') }} {{ $total }}</span>
                </div>
                @if ($total === 0)
                    <div class="flex h-28 flex-col items-center justify-center gap-1 text-slate-300">
                        @svg('lucide-bar-chart-3', 'h-7 w-7')
                        <span class="text-xs text-slate-400">{{ __('msg_dashboard.no_data') }}</span>
                    </div>
                @else
                    <div class="flex h-28 items-end gap-px">
                        @foreach ($chart['series'] as $point)
                            {{-- A coluna precisa de altura definida (h-full) para a barra
                                 interna em % renderizar; flex items-end ancora no fundo. --}}
                            <div class="group relative flex h-full flex-1 items-end" title="{{ $point['label'] }}: {{ $point['value'] }}">
                                <div class="{{ $barColors[$chart['color']] ?? 'bg-slate-400' }} w-full rounded-t transition-all hover:opacity-80"
                                     style="height: {{ max(2, (int) round($point['value'] / $max * 100)) }}%"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-2 flex justify-between text-[10px] text-slate-400">
                        <span>{{ $chart['series']->first()['label'] }}</span>
                        <span>{{ $chart['series']->last()['label'] }}</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Terreiros recentes --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h3 class="font-semibold text-slate-700">{{ __('page_admin_dashboard.recent_terreiros') }}</h3>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($recentTerreiros as $terreiro)
                <li class="flex items-center justify-between px-6 py-3 text-sm">
                    <span class="font-medium text-slate-700">{{ $terreiro->name }}</span>
                    <span class="text-slate-400">{{ $terreiro->created_at?->format('d/m/Y') }}</span>
                </li>
            @empty
                <li class="px-6 py-8 text-center text-sm text-slate-400">{{ __('page_admin_dashboard.no_terreiros') }}</li>
            @endforelse
        </ul>
    </div>
</div>
