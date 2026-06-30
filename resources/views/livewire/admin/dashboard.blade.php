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

    {{-- Gráficos 30 dias --}}
    @php
        $barColors = ['sky' => 'bg-sky-500', 'violet' => 'bg-violet-500', 'amber' => 'bg-amber-500'];
    @endphp
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        @foreach ($charts as $chart)
            @php $max = max(1, $chart['series']->max('value')); @endphp
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-baseline justify-between">
                    <h3 class="text-sm font-semibold text-slate-700">{{ $chart['title'] }}</h3>
                    <span class="text-xs text-slate-400">{{ __('page_admin_dashboard.chart_total') }} {{ $chart['series']->sum('value') }}</span>
                </div>
                <div class="flex h-28 items-end gap-px">
                    @foreach ($chart['series'] as $point)
                        <div class="group relative flex-1" title="{{ $point['label'] }}: {{ $point['value'] }}">
                            <div class="{{ $barColors[$chart['color']] ?? 'bg-slate-400' }} rounded-t transition-all hover:opacity-80"
                                 style="height: {{ max(2, (int) round($point['value'] / $max * 100)) }}%"></div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-2 flex justify-between text-[10px] text-slate-400">
                    <span>{{ $chart['series']->first()['label'] }}</span>
                    <span>{{ $chart['series']->last()['label'] }}</span>
                </div>
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
