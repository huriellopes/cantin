<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ __('crud_impersonation_logs.title') }}</h2>
            <p class="mt-1 text-sm text-slate-500">{{ __('crud_impersonation_logs.subtitle') }}</p>
        </div>
    </div>

    <x-admin.card>
        <div class="border-b border-slate-100 p-4">
            <x-admin.table-toolbar :options="$this->perPageOptions()" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <x-admin.th column="id" :sort-field="$sortField" :sort-direction="$sortDirection">#</x-admin.th>
                        <th class="px-4 py-3">{{ __('crud_impersonation_logs.impersonator') }}</th>
                        <th class="px-4 py-3">{{ __('crud_impersonation_logs.impersonated') }}</th>
                        <x-admin.th column="action" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_impersonation_logs.action') }}</x-admin.th>
                        <x-admin.th column="ip" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_impersonation_logs.ip') }}</x-admin.th>
                        <x-admin.th column="created_at" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_impersonation_logs.date') }}</x-admin.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50" wire:key="log-{{ $log->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $log->id }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $log->impersonator?->name ?? '—' }}<span class="block text-xs text-slate-400">{{ $log->impersonator?->email }}</span></td>
                            <td class="px-4 py-3 text-slate-700">{{ $log->impersonated?->name ?? '—' }}<span class="block text-xs text-slate-400">{{ $log->impersonated?->email }}</span></td>
                            <td class="px-4 py-3">
                                <x-admin.badge :color="$log->action === 'started' ? 'emerald' : 'slate'">
                                    {{ $log->action === 'started' ? __('crud_impersonation_logs.started') : __('crud_impersonation_logs.stopped') }}
                                </x-admin.badge>
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $log->ip ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">{{ __('crud_impersonation_logs.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $logs->links() }}</div>
    </x-admin.card>
</div>
