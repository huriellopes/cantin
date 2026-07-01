<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">{{ __('crud_audits.title') }}</h2>
        <p class="mt-1 text-sm text-slate-500">{{ __('crud_audits.subtitle') }}</p>
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
                        <th class="px-4 py-3">{{ __('crud_audits.user') }}</th>
                        <x-admin.th column="event" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_audits.event') }}</x-admin.th>
                        <x-admin.th column="auditable_type" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_audits.model') }}</x-admin.th>
                        <x-admin.th column="created_at" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_audits.date') }}</x-admin.th>
                        <th class="px-4 py-3 text-right">{{ __('crud_audits.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $eventColors = ['created' => 'emerald', 'updated' => 'amber', 'deleted' => 'rose', 'restored' => 'sky'];
                    @endphp
                    @forelse ($audits as $audit)
                        <tr class="hover:bg-slate-50" wire:key="audit-{{ $audit->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $audit->id }}</td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ $audit->user?->name ?? __('crud_audits.system') }}
                                <span class="block text-xs text-slate-400">{{ $audit->user?->email }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <x-admin.badge :color="$eventColors[$audit->event] ?? 'slate'">
                                    {{ __('crud_audits.events.' . $audit->event) }}
                                </x-admin.badge>
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ class_basename((string) $audit->auditable_type) }}
                                <span class="text-xs text-slate-400">#{{ $audit->auditable_id }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $audit->created_at?->format('d/m/Y H:i:s') }}</td>
                            <td class="px-4 py-3 text-right">
                                <button type="button" wire:click="view({{ $audit->id }})"
                                        title="{{ __('crud_audits.view') }}" aria-label="{{ __('crud_audits.view') }}"
                                        class="inline-flex items-center rounded-lg p-1.5 text-slate-500 transition hover:bg-slate-100 hover:text-violet-700">
                                    @svg('lucide-eye', 'h-4 w-4')
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">{{ __('crud_audits.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $audits->links() }}</div>
    </x-admin.card>

    {{-- Modal de detalhes da auditoria --}}
    <x-admin.modal :title="__('crud_audits.detail_title')">
        @if ($auditMeta)
            <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('crud_audits.event') }}</dt><dd class="text-sm text-slate-700">{{ __('crud_audits.events.' . $auditMeta['event']) }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('crud_audits.model') }}</dt><dd class="text-sm text-slate-700">{{ $auditMeta['type'] }} #{{ $auditMeta['id'] }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('crud_audits.user') }}</dt><dd class="text-sm text-slate-700">{{ $auditMeta['user'] }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('crud_audits.date') }}</dt><dd class="text-sm text-slate-700">{{ $auditMeta['date'] }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('crud_audits.ip') }}</dt><dd class="text-sm text-slate-700">{{ $auditMeta['ip'] ?? '—' }}</dd></div>
            </dl>

            <div class="mt-5">
                <h4 class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('crud_audits.changes') }}</h4>
                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-3 py-2">{{ __('crud_audits.field') }}</th>
                                <th class="px-3 py-2">{{ __('crud_audits.old') }}</th>
                                <th class="px-3 py-2">{{ __('crud_audits.new') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($modified as $row)
                                <tr>
                                    <td class="px-3 py-2 font-medium text-slate-600">{{ $row['field'] }}</td>
                                    <td class="px-3 py-2 text-rose-600"><span class="break-words">{{ $row['old'] }}</span></td>
                                    <td class="px-3 py-2 text-emerald-700"><span class="break-words">{{ $row['new'] }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-3 py-4 text-center text-slate-400">{{ __('crud_audits.no_changes') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </x-admin.modal>
</div>
