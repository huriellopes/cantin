<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">{{ __('crud_system.title') }}</h2>
        <p class="mt-1 text-sm text-slate-500">{{ __('crud_system.subtitle') }}</p>
    </div>

    {{-- Abas --}}
    @php
        $tabs = [
            'logs' => ['file-text', __('crud_system.tab_logs')],
            'debug' => ['bug', __('crud_system.tab_debug')],
            'schedules' => ['calendar-clock', __('crud_system.tab_schedules')],
            'jobs' => ['list-checks', __('crud_system.tab_jobs')],
        ];
    @endphp
    <div class="flex flex-wrap gap-1 border-b border-slate-200">
        @foreach ($tabs as $key => [$icon, $label])
            <button type="button" wire:click="setTab('{{ $key }}')"
                    @class([
                        'inline-flex items-center gap-2 border-b-2 px-4 py-2.5 text-sm font-medium transition',
                        'border-violet-600 text-violet-700' => $tab === $key,
                        'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' => $tab !== $key,
                    ])>
                @svg('lucide-'.$icon, 'h-4 w-4')
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- ========================= LOGS ========================= --}}
    @if ($tab === 'logs')
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-4">
            {{-- Lista de arquivos --}}
            <x-admin.card class="lg:col-span-1">
                <div class="border-b border-slate-100 px-4 py-3">
                    <h3 class="text-sm font-semibold text-slate-600">{{ __('crud_system.log_files') }}</h3>
                </div>
                <ul class="max-h-[70vh] divide-y divide-slate-100 overflow-y-auto">
                    @forelse ($logFiles as $file)
                        <li>
                            <button type="button" wire:click="selectLog('{{ $file['name'] }}')"
                                    @class([
                                        'flex w-full items-center justify-between gap-2 px-4 py-3 text-left text-sm transition',
                                        'bg-violet-50 text-violet-700' => $logFile === $file['name'],
                                        'text-slate-600 hover:bg-slate-50' => $logFile !== $file['name'],
                                    ])>
                                <span class="truncate font-medium">{{ $file['name'] }}</span>
                                <span class="shrink-0 text-xs text-slate-400">{{ number_format($file['size'] / 1024, 0, ',', '.') }} KB</span>
                            </button>
                        </li>
                    @empty
                        <li class="px-4 py-8 text-center text-sm text-slate-400">{{ __('crud_system.no_logs') }}</li>
                    @endforelse
                </ul>
            </x-admin.card>

            {{-- Entradas do arquivo selecionado --}}
            <x-admin.card class="lg:col-span-3">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <select wire:model.live="logLevel"
                                class="rounded-lg border border-slate-300 py-2 pl-3 pr-8 text-sm focus:border-violet-500 focus:ring-violet-500">
                            <option value="">{{ __('crud_system.all_levels') }}</option>
                            @foreach ($logLevels as $level)
                                <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                            @endforeach
                        </select>
                        <input type="search" wire:model.live.debounce.400ms="logSearch"
                               placeholder="{{ __('common.search') }}"
                               class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
                    </div>
                    @if ($logFile !== '')
                        <button type="button" wire:click="confirmClearLog('{{ $logFile }}')"
                                class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-white px-3 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                            @svg('lucide-eraser', 'h-4 w-4')
                            {{ __('crud_system.clear_log') }}
                        </button>
                    @endif
                </div>

                <div class="max-h-[70vh] divide-y divide-slate-100 overflow-y-auto">
                    @php
                        $levelColor = [
                            'emergency' => 'danger', 'alert' => 'danger', 'critical' => 'danger', 'error' => 'danger',
                            'warning' => 'warning', 'notice' => 'primary', 'info' => 'primary', 'debug' => 'slate',
                        ];
                    @endphp
                    @forelse ($logEntries as $entry)
                        <div class="px-4 py-3 text-sm" wire:key="entry-{{ $loop->index }}">
                            <div class="flex flex-wrap items-center gap-2">
                                <x-admin.badge :color="$levelColor[$entry['level']] ?? 'slate'">{{ strtoupper($entry['level']) }}</x-admin.badge>
                                <span class="text-xs text-slate-400">{{ $entry['datetime'] }}</span>
                                <span class="text-xs text-slate-300">{{ $entry['env'] }}</span>
                            </div>
                            <p class="mt-1 break-words font-medium text-slate-700">{{ $entry['message'] }}</p>
                            @if ($entry['context'] !== '')
                                <details class="mt-1">
                                    <summary class="cursor-pointer text-xs text-slate-400 hover:text-slate-600">{{ __('crud_system.details') }}</summary>
                                    <pre class="mt-1 max-h-64 overflow-auto rounded-lg bg-slate-900 p-3 text-xs text-slate-200">{{ $entry['context'] }}</pre>
                                </details>
                            @endif
                        </div>
                    @empty
                        <div class="px-4 py-10 text-center text-sm text-slate-400">{{ __('crud_system.no_entries') }}</div>
                    @endforelse
                </div>
            </x-admin.card>
        </div>
    @endif

    {{-- ========================= DEBUG ========================= --}}
    @if ($tab === 'debug')
        <x-admin.card>
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-4">
                <h3 class="text-sm font-semibold text-slate-600">{{ __('crud_system.debug_captures') }}</h3>
                <button type="button" wire:click="confirmClearDebug"
                        class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-white px-3 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                    @svg('lucide-trash-2', 'h-4 w-4')
                    {{ __('crud_system.clear_debug') }}
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">{{ __('crud_system.method') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.uri') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.status') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.duration') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.date') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($captures as $capture)
                            <tr @class(['hover:bg-slate-50', 'bg-rose-50/50' => $capture['has_exceptions']]) wire:key="cap-{{ $capture['id'] }}">
                                <td class="px-4 py-3"><x-admin.badge color="slate">{{ $capture['method'] }}</x-admin.badge></td>
                                <td class="px-4 py-3 font-medium text-slate-700">
                                    {{ $capture['uri'] }}
                                    @if ($capture['has_exceptions'])
                                        <span class="ml-1 text-rose-500" title="{{ __('crud_system.has_exceptions') }}">@svg('lucide-alert-triangle', 'inline h-4 w-4')</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-500">{{ $capture['status'] ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $capture['duration'] ? number_format($capture['duration'] * 1000, 0) . ' ms' : '—' }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $capture['time'] ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" wire:click="showCapture('{{ $capture['id'] }}')"
                                            class="inline-flex items-center gap-1 text-sm font-medium text-violet-600 hover:text-violet-800">
                                        @svg('lucide-eye', 'h-4 w-4') {{ __('crud_system.view') }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">{{ __('crud_system.no_captures') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin.card>

        {{-- Modal de detalhe da captura.
             O fechamento é feito instantaneamente no cliente (Alpine: open=false)
             e depois sincronizado no servidor ($wire.closeCapture()), então nunca
             depende de um round-trip do Livewire para sumir da tela. --}}
        @if ($capture !== null)
            <div wire:key="capture-modal"
                 x-data="{ open: true, close() { this.open = false; $wire.closeCapture() } }"
                 x-show="open"
                 x-trap.noscroll="open"
                 @keydown.escape.window="close()"
                 class="fixed inset-0 z-[70] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-900/50" @click="close()"></div>
                <div class="relative z-10 flex max-h-[85vh] w-full max-w-3xl flex-col rounded-2xl bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                        <h3 class="font-semibold text-slate-800">{{ __('crud_system.capture_detail') }}</h3>
                        <button type="button" @click="close()" class="text-slate-400 hover:text-slate-600">@svg('lucide-x', 'h-5 w-5')</button>
                    </div>
                    <pre class="flex-1 overflow-auto rounded-b-2xl bg-slate-900 p-4 text-xs text-slate-200">{{ json_encode($capture, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif
    @endif

    {{-- ========================= SCHEDULES ========================= --}}
    @if ($tab === 'schedules')
        <x-admin.card>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">{{ __('crud_system.command') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.expression') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.frequency') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.next_run') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($schedules as $event)
                            <tr class="hover:bg-slate-50" wire:key="sch-{{ $loop->index }}">
                                <td class="px-4 py-3">
                                    <span class="font-mono font-medium text-slate-700">{{ $event['command'] }}</span>
                                    @if ($event['description'])
                                        <span class="block text-xs text-slate-400">{{ $event['description'] }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3"><code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600">{{ $event['expression'] }}</code></td>
                                <td class="px-4 py-3 text-slate-600">{{ $event['human'] }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $event['next_run'] ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-10 text-center text-slate-400">{{ __('crud_system.no_schedules') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin.card>
    @endif

    {{-- ========================= JOBS / QUEUE ========================= --}}
    @if ($tab === 'jobs')
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 text-sky-600">@svg('lucide-hourglass', 'h-6 w-6')</div>
                <div><p class="text-sm text-slate-500">{{ __('crud_system.pending_jobs') }}</p><p class="text-2xl font-bold text-slate-800">{{ number_format($queueCounts['pending'], 0, ',', '.') }}</p></div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100 text-rose-600">@svg('lucide-circle-x', 'h-6 w-6')</div>
                <div><p class="text-sm text-slate-500">{{ __('crud_system.failed_jobs') }}</p><p class="text-2xl font-bold text-slate-800">{{ number_format($queueCounts['failed'], 0, ',', '.') }}</p></div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-600">@svg('lucide-settings-2', 'h-6 w-6')</div>
                <div><p class="text-sm text-slate-500">{{ __('crud_system.connection') }}</p><p class="text-lg font-bold text-slate-800">{{ $queueCounts['connection'] }}</p></div>
            </div>
        </div>

        {{-- Pendentes --}}
        <x-admin.card>
            <div class="border-b border-slate-100 px-4 py-3">
                <h3 class="text-sm font-semibold text-slate-600">{{ __('crud_system.pending_jobs') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">{{ __('crud_system.job') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.queue') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.attempts') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.available_at') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pendingJobs ?? [] as $job)
                            <tr class="hover:bg-slate-50" wire:key="pending-{{ $job->id }}">
                                <td class="px-4 py-3 text-slate-400">{{ $job->id }}</td>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $job->name }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $job->queue }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $job->attempts }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $job->available_at_human ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">{{ __('crud_system.no_pending') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($pendingJobs && $pendingJobs->hasPages())
                <div class="border-t border-slate-100 p-4">{{ $pendingJobs->links() }}</div>
            @endif
        </x-admin.card>

        {{-- Falhos --}}
        <x-admin.card>
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-3">
                <h3 class="text-sm font-semibold text-slate-600">{{ __('crud_system.failed_jobs') }}</h3>
                @if ($queueCounts['failed'] > 0)
                    <div class="flex gap-2">
                        <button type="button" wire:click="retryAllFailed"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                            @svg('lucide-refresh-cw', 'h-4 w-4') {{ __('crud_system.retry_all') }}
                        </button>
                        <button type="button" wire:click="confirmFlushFailed"
                                class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-white px-3 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                            @svg('lucide-trash-2', 'h-4 w-4') {{ __('crud_system.flush_all') }}
                        </button>
                    </div>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">{{ __('crud_system.job') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.queue') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.exception') }}</th>
                            <th class="px-4 py-3">{{ __('crud_system.failed_at') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($failedJobs ?? [] as $job)
                            <tr class="hover:bg-slate-50" wire:key="failed-{{ $job->id }}">
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $job->name }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $job->queue }}</td>
                                <td class="px-4 py-3 max-w-md truncate text-rose-600" title="{{ $job->exception_short }}">{{ $job->exception_short }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ \Illuminate\Support\Carbon::parse($job->failed_at)->format('d/m/Y H:i:s') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button type="button" wire:click="retryJob('{{ $job->uuid }}')" class="text-sm font-medium text-violet-600 hover:text-violet-800" title="{{ __('crud_system.retry') }}">@svg('lucide-refresh-cw', 'h-4 w-4')</button>
                                        <button type="button" wire:click="confirmForgetFailed('{{ $job->uuid }}')" class="text-sm font-medium text-rose-600 hover:text-rose-800" title="{{ __('crud_system.forget') }}">@svg('lucide-trash-2', 'h-4 w-4')</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">{{ __('crud_system.no_failed') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($failedJobs && $failedJobs->hasPages())
                <div class="border-t border-slate-100 p-4">{{ $failedJobs->links() }}</div>
            @endif
        </x-admin.card>
    @endif

    <x-admin.confirm :confirm="$confirm" />
</div>
