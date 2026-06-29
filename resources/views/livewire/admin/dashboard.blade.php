<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Olá, {{ auth()->user()->name }} 👋</h2>
        <p class="mt-1 text-sm text-slate-500">Aqui está um resumo do CaNTIn hoje.</p>
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
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        @switch($stat['icon'])
                            @case('eye') <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1 1 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178a1 1 0 0 1 0 .644C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/> @break
                            @case('home') <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/> @break
                            @case('chat') <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/> @break
                            @case('users') <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/> @break
                            @case('star') <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/> @break
                            @default <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        @endswitch
                    </svg>
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
                    <span class="text-xs text-slate-400">total {{ $chart['series']->sum('value') }}</span>
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
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h3 class="font-semibold text-slate-700">Terreiros recentes</h3>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($recentTerreiros as $terreiro)
                <li class="flex items-center justify-between px-6 py-3 text-sm">
                    <span class="font-medium text-slate-700">{{ $terreiro->name }}</span>
                    <span class="text-slate-400">{{ $terreiro->created_at?->format('d/m/Y') }}</span>
                </li>
            @empty
                <li class="px-6 py-8 text-center text-sm text-slate-400">Nenhum terreiro cadastrado ainda.</li>
            @endforelse
        </ul>
    </div>
</div>
