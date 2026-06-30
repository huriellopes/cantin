<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Terreiros</h2>
            <p class="text-sm text-slate-500">Cadastro de terreiros e questionário de acolhimento.</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="exportCsv" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                Exportar CSV
            </button>
            <button wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">
                @svg('lucide-plus', 'h-4 w-4')
                Novo terreiro
            </button>
        </div>
    </div>


    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Buscar por nome..."
                   class="w-full max-w-sm rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">Telefone</th>
                        <th class="px-4 py-3">Liderança</th>
                        <th class="px-4 py-3">Nação</th>
                        <th class="px-4 py-3">Cidade/UF</th>
                        <th class="px-4 py-3 text-right">Ações</th>
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
                                    <x-admin.action icon="view" color="sky" label="Visualizar" wire:click="view({{ $terreiro->id }})" />
                                    <x-admin.action icon="edit" color="violet" label="Editar" wire:click="edit({{ $terreiro->id }})" />
                                    <x-admin.action icon="delete" color="rose" label="Excluir" wire:click="confirmDelete({{ $terreiro->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-10 text-center text-slate-400">Nenhum terreiro encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 p-4">{{ $terreiros->links() }}</div>
    </div>

    <x-admin.modal title="{{ $editingId ? 'Editar terreiro' : 'Novo terreiro' }}">
        <form wire:submit="save" class="space-y-6">
            {{-- Dados --}}
            <section class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Dados do terreiro</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-admin.input label="Nome" name="name" wire:model="name" />
                    <x-admin.input label="Telefone" name="phone" wire:model="phone" x-mask="(99) 9 9999-9999" />
                    <x-admin.input label="Orukó / nome da liderança" name="leadership_orunko" wire:model="leadership_orunko" />
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Nação</label>
                        <select wire:model="nation_terreiro_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach ($nations as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
                        </select>
                        @error('nation_terreiro_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Cor da liderança</label>
                        <select wire:model="color_of_leadership" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach ($config['color_of_leadership'] as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                        </select>
                        @error('color_of_leadership') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- Endereço --}}
            <section class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Endereço</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="flex items-end gap-2">
                        <div class="flex-1"><x-admin.input label="CEP" name="zipcode" wire:model="zipcode" x-mask="99999-999" /></div>
                        <button type="button" wire:click="buscarCep" class="mb-0.5 rounded-lg bg-slate-100 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-200">Buscar</button>
                    </div>
                    <x-admin.input label="Endereço" name="address" wire:model="address" />
                    <x-admin.input label="Complemento" name="complement" wire:model="complement" />
                    <x-admin.input label="Bairro" name="neighborhood" wire:model="neighborhood" />
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Estado</label>
                        <select wire:model.live="state_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach ($states as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
                        </select>
                        @error('state_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Cidade</label>
                        <select wire:model="city_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach ($cities as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
                        </select>
                        @error('city_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- Questionário --}}
            <section class="space-y-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-400">Questionário de acolhimento</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700">Identidade de gênero da liderança</label>
                        <select wire:model="type_people_id" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach ($typePeoples as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach
                        </select>
                        @error('type_people_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <x-admin.input label="Membros ativos" name="number_of_children_of_saint" type="number" wire:model="number_of_children_of_saint" />
                    <x-admin.input label="Integrantes trans" name="number_of_children_of_saint_trans" type="number" wire:model="number_of_children_of_saint_trans" />

                    @foreach ([
                        'trans_men_and_women' => 'Acolhe homens e mulheres trans?',
                        'name_gender' => 'Respeita o nome social?',
                        'fully_welcomes' => 'Acolhe plenamente?',
                        'respect_for_trans_people' => 'Respeito às pessoas trans?',
                        'suffered_aggregation' => 'Já sofreu agressão?',
                        'inclusion_of_the_name_of_the_land' => 'Inclusão do nome no terreiro?',
                        'suggestion_id' => 'Sugestão',
                    ] as $field => $label)
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-700">{{ $label }}</label>
                            <select wire:model="{{ $field }}" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <option value="">Selecione...</option>
                                @foreach (($config[$field] ?? []) as $value => $optLabel)<option value="{{ $value }}">{{ $optLabel }}</option>@endforeach
                            </select>
                            @error($field) <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    @endforeach

                    <div class="space-y-1 sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Texto da sugestão (se aplicável)</label>
                        <textarea wire:model="suggestion_text" rows="2" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                        @error('suggestion_text') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" @click="$wire.showModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                    <span wire:loading.remove wire:target="save">Salvar</span>
                    <span wire:loading wire:target="save">Salvando...</span>
                </button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.view :show="$showView" :title="$viewTitle" :data="$viewData" />
    <x-admin.confirm :confirm="$confirm" />
</div>
