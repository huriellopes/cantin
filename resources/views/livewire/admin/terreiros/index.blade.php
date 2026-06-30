<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ __('crud_terreiros.title') }}</h2>
            <p class="text-sm text-slate-500">{{ __('crud_terreiros.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" wire:click="export" wire:loading.attr="disabled" wire:target="export"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 disabled:opacity-70">
                @svg('lucide-file-spreadsheet', 'h-4 w-4')
                <span wire:loading.remove wire:target="export">{{ __('exports.export') }}</span>
                <span wire:loading wire:target="export">{{ __('common.exporting') }}</span>
            </button>
            <button wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">
                @svg('lucide-plus', 'h-4 w-4')
                {{ __('crud_terreiros.new_terreiro') }}
            </button>
        </div>
    </div>


    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <x-admin.table-toolbar :options="$this->perPageOptions()" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <x-admin.th column="id" :sort-field="$sortField" :sort-direction="$sortDirection">#</x-admin.th>
                        <x-admin.th column="name" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_terreiros.col_name') }}</x-admin.th>
                        <x-admin.th column="phone" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_terreiros.col_phone') }}</x-admin.th>
                        <x-admin.th column="leadership_orunko" :sort-field="$sortField" :sort-direction="$sortDirection">{{ __('crud_terreiros.col_leadership') }}</x-admin.th>
                        <th class="px-4 py-3">{{ __('crud_terreiros.col_nation') }}</th>
                        <th class="px-4 py-3">{{ __('crud_terreiros.col_city_uf') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($terreiros as $terreiro)
                        <tr class="hover:bg-slate-50" wire:key="terreiro-{{ $terreiro->id }}">
                            <td class="px-4 py-3 text-slate-400">{{ $terreiro->id }}</td>
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $terreiro->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $terreiro->phone }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $terreiro->leadership_orunko }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $terreiro->nation?->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $terreiro->address?->city?->name }}{{ $terreiro->address?->state ? ' / '.$terreiro->address->state->name : '' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-admin.action icon="view" color="sky" label="{{ __('common.view') }}" wire:click="view({{ $terreiro->id }})" />
                                    <x-admin.action icon="edit" color="violet" label="{{ __('common.edit') }}" wire:click="edit({{ $terreiro->id }})" />
                                    <x-admin.action icon="delete" color="rose" label="{{ __('common.delete') }}" wire:click="confirmDelete({{ $terreiro->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-10 text-center text-slate-400">{{ __('crud_terreiros.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $terreiros->links() }}</div>
    </div>

    <x-admin.modal title="{{ $editingId ? __('crud_terreiros.modal_edit_title') : __('crud_terreiros.modal_new_title') }}">
        <form wire:submit="save" class="space-y-6">
            {{-- Dados --}}
            <section class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-400">{{ __('crud_terreiros.section_data') }}</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-admin.input label="{{ __('crud_terreiros.field_name') }}" name="name" wire:model="name" />
                    <x-admin.input label="{{ __('crud_terreiros.field_phone') }}" name="phone" wire:model="phone" x-mask="(99) 9 9999-9999" />
                    <x-admin.input label="{{ __('crud_terreiros.field_leadership_orunko') }}" name="leadership_orunko" wire:model="leadership_orunko" />
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">{{ __('crud_terreiros.field_nation') }}</label>
                        <select wire:model="nation_terreiro_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">{{ __('crud_terreiros.select_placeholder') }}</option>
                            @foreach ($nations as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
                        </select>
                        @error('nation_terreiro_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">{{ __('crud_terreiros.field_color_of_leadership') }}</label>
                        <select wire:model="color_of_leadership" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">{{ __('crud_terreiros.select_placeholder') }}</option>
                            @foreach ($config['color_of_leadership'] as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                        </select>
                        @error('color_of_leadership') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- Endereço --}}
            <section class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-400">{{ __('crud_terreiros.section_address') }}</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="flex items-end gap-2">
                        <div class="flex-1"><x-admin.input label="{{ __('crud_terreiros.field_zipcode') }}" name="zipcode" wire:model="zipcode" x-mask="99999-999" /></div>
                        <button type="button" wire:click="buscarCep" class="mb-0.5 rounded-lg bg-slate-100 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-200">{{ __('common.search') }}</button>
                    </div>
                    <x-admin.input label="{{ __('crud_terreiros.field_address') }}" name="address" wire:model="address" />
                    <x-admin.input label="{{ __('crud_terreiros.field_complement') }}" name="complement" wire:model="complement" />
                    <x-admin.input label="{{ __('crud_terreiros.field_neighborhood') }}" name="neighborhood" wire:model="neighborhood" />
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">{{ __('crud_terreiros.field_state') }}</label>
                        <select wire:model.live="state_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">{{ __('crud_terreiros.select_placeholder') }}</option>
                            @foreach ($states as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
                        </select>
                        @error('state_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">{{ __('crud_terreiros.field_city') }}</label>
                        <select wire:model="city_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">{{ __('crud_terreiros.select_placeholder') }}</option>
                            @foreach ($cities as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
                        </select>
                        @error('city_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- Questionário --}}
            <section class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-400">{{ __('crud_terreiros.section_questionnaire') }}</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">{{ __('crud_terreiros.field_gender_identity') }}</label>
                        <select wire:model="type_people_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">{{ __('crud_terreiros.select_placeholder') }}</option>
                            @foreach ($typePeoples as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
                        </select>
                        @error('type_people_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <x-admin.input label="{{ __('crud_terreiros.field_active_members') }}" name="number_of_children_of_saint" type="number" wire:model="number_of_children_of_saint" />
                    <x-admin.input label="{{ __('crud_terreiros.field_trans_members') }}" name="number_of_children_of_saint_trans" type="number" wire:model="number_of_children_of_saint_trans" />

                    @foreach ([
                        'trans_men_and_women' => __('crud_terreiros.q_trans_men_and_women'),
                        'name_gender' => __('crud_terreiros.q_name_gender'),
                        'fully_welcomes' => __('crud_terreiros.q_fully_welcomes'),
                        'respect_for_trans_people' => __('crud_terreiros.q_respect_for_trans_people'),
                        'suffered_aggregation' => __('crud_terreiros.q_suffered_aggregation'),
                        'inclusion_of_the_name_of_the_land' => __('crud_terreiros.q_inclusion_of_the_name_of_the_land'),
                        'suggestion_id' => __('crud_terreiros.q_suggestion'),
                    ] as $field => $label)
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-700">{{ $label }}</label>
                            <select wire:model="{{ $field }}" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <option value="">{{ __('crud_terreiros.select_placeholder') }}</option>
                                @foreach (($config[$field] ?? []) as $value => $optLabel)<option value="{{ $value }}">{{ $optLabel }}</option>@endforeach
                            </select>
                            @error($field) <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    @endforeach

                    <div class="space-y-1 sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">{{ __('crud_terreiros.field_suggestion_text') }}</label>
                        <textarea wire:model="suggestion_text" rows="2" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                        @error('suggestion_text') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">{{ __('common.cancel') }}</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                    <span wire:loading.remove wire:target="save">{{ __('common.save') }}</span>
                    <span wire:loading wire:target="save">{{ __('common.saving') }}</span>
                </button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
