@php
    $field = 'block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500';
    $label = 'mb-1 block text-sm font-medium text-slate-700';
    $err = 'mt-1 text-xs text-rose-600';
@endphp

<div class="mx-auto max-w-3xl px-6 py-16">
    <header class="text-center">
        <h1 class="text-3xl font-extrabold text-slate-800 sm:text-4xl">{{ __('page_terreiros_create.page_title') }}</h1>
    </header>

    {{-- Steps --}}
    <div class="mt-8 flex items-center justify-center gap-4">
        @foreach ([__('page_terreiros_create.step_terreiro_data'), __('page_terreiros_create.step_questions')] as $i => $stepLabel)
            @php $n = $i + 1; @endphp
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold text-white {{ $currentStep >= $n ? 'bg-violet-600' : 'bg-slate-300' }}">{{ $n }}</span>
                <span class="text-sm font-medium {{ $currentStep >= $n ? 'text-slate-800' : 'text-slate-400' }}">{{ $stepLabel }}</span>
            </div>
            @if (! $loop->last)<span class="h-px w-10 bg-slate-300"></span>@endif
        @endforeach
    </div>

    <form wire:submit.prevent="store" class="mt-10 space-y-5">
        @csrf
        @if ($currentStep === 1)
            <fieldset class="rounded-2xl border border-slate-200 p-5">
                <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-500">{{ __('page_terreiros_create.fieldset_terreiro_data') }}</legend>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="name" class="{{ $label }}">{{ __('page_terreiros_create.label_name') }}</label>
                        <input type="text" id="name" wire:model.live="name" class="{{ $field }}" />
                        @error('name') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="nation_terreiro_id" class="{{ $label }}">{{ __('Nation') }}</label>
                            <select id="nation_terreiro_id" wire:model.live="nation_terreiro_id" class="{{ $field }}">
                                <option value="">{{ __('Select the nation') }}</option>
                                @foreach ($nations as $nation)<option value="{{ $nation->id }}">{{ $nation->name }}</option>@endforeach
                            </select>
                            @error('nation_terreiro_id') <p class="{{ $err }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="{{ $label }}">{{ __('page_terreiros_create.label_phone') }}</label>
                            <input type="text" id="phone" x-mask="(99) 9 9999-9999" wire:model.live="phone" class="{{ $field }}" />
                            @error('phone') <p class="{{ $err }}">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="leadership_orunko" class="{{ $label }}">{{ __('page_terreiros_create.label_leadership_orunko') }}</label>
                            <input type="text" id="leadership_orunko" wire:model.live="leadership_orunko" class="{{ $field }}" />
                            @error('leadership_orunko') <p class="{{ $err }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="color_of_leadership" class="{{ $label }}">{{ __('page_terreiros_create.label_color_of_leadership') }}</label>
                            <select id="color_of_leadership" wire:model.live="color_of_leadership" class="{{ $field }}">
                                <option value="">{{ __('page_terreiros_create.select_color_of_leadership') }}</option>
                                @foreach (config('color-leader.list') as $color => $name)<option value="{{ $color }}">{{ $name }}</option>@endforeach
                            </select>
                            @error('color_of_leadership') <p class="{{ $err }}">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="rounded-2xl border border-slate-200 p-5">
                <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-500">{{ __('page_terreiros_create.fieldset_address') }}</legend>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="zipcode" class="{{ $label }}">{{ __('Zip Code') }}</label>
                        <div class="flex gap-2">
                            <input type="text" id="zipcode" maxlength="9" x-mask="99999-999" wire:model.live="zipcode" class="{{ $field }}" />
                            <button type="button" wire:click="searchZipCode" wire:loading.attr="disabled" wire:target="searchZipCode" class="shrink-0 rounded-lg bg-slate-100 px-3 text-sm font-medium text-slate-600 hover:bg-slate-200">
                                <span wire:loading.remove wire:target="searchZipCode">{{ __('common.search') }}</span><span wire:loading wire:target="searchZipCode">...</span>
                            </button>
                        </div>
                        @error('zipcode') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="street" class="{{ $label }}">{{ __('Address') }}</label>
                        <input type="text" id="street" wire:model.live="street" class="{{ $field }}" />
                        @error('street') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="complement" class="{{ $label }}">{{ __('Complement') }}</label>
                        <input type="text" id="complement" wire:model.live="complement" class="{{ $field }}" />
                        @error('complement') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="neighborhood" class="{{ $label }}">{{ __('Neighborhood') }}</label>
                        <input type="text" id="neighborhood" wire:model.live="neighborhood" class="{{ $field }}" />
                        @error('neighborhood') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="state_id" class="{{ $label }}">{{ __('State') }}</label>
                        <select id="state_id" wire:model.live="state_id" class="{{ $field }}">
                            <option value="">{{ __('Select the state') }}</option>
                            @foreach ($states as $state)<option value="{{ $state->id }}">{{ $state->name }}</option>@endforeach
                        </select>
                        @error('state_id') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="city_id" class="{{ $label }}">{{ __('City') }}</label>
                        <select id="city_id" wire:model.live="city_id" wire:loading.attr="disabled" wire:target="state_id" class="{{ $field }}">
                            <option value="">{{ __('Select the city') }}</option>
                            @if (! empty($cities))@foreach ($cities as $city)<option value="{{ $city->id }}">{{ $city->name }}</option>@endforeach @endif
                        </select>
                        @error('city_id') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            <div class="flex justify-end">
                <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
                        class="rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-7 py-3 font-semibold text-white shadow-md transition hover:brightness-110">
                    {{ __('page_terreiros_create.next_step') }}
                </button>
            </div>
        @elseif ($currentStep === 2)
            <fieldset class="rounded-2xl border border-slate-200 p-5">
                <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-500">{{ __('page_terreiros_create.fieldset_questionnaire') }}</legend>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="type_people_id" class="{{ $label }}">{{ __('page_terreiros_create.label_type_people') }}</label>
                        <select id="type_people_id" wire:model.live="type_people_id" class="{{ $field }}">
                            <option value="">{{ __('page_terreiros_create.select_type_people') }}</option>
                            @foreach ($typePeoples as $typePeople)<option value="{{ $typePeople->id }}">{{ $typePeople->name }}</option>@endforeach
                        </select>
                        @error('type_people_id') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="number_of_children_of_saint" class="{{ $label }}">{{ __('page_terreiros_create.label_number_of_children_of_saint') }}</label>
                        <input type="number" id="number_of_children_of_saint" wire:model.live="number_of_children_of_saint" class="{{ $field }}" />
                        @error('number_of_children_of_saint') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="number_of_children_of_saint_trans" class="{{ $label }}">{{ __('page_terreiros_create.label_number_of_children_of_saint_trans') }}</label>
                        <input type="number" id="number_of_children_of_saint_trans" wire:model.live="number_of_children_of_saint_trans" class="{{ $field }}" />
                        @error('number_of_children_of_saint_trans') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="trans_men_and_women" class="{{ $label }}">{{ __('page_terreiros_create.label_trans_men_and_women') }}</label>
                        <select id="trans_men_and_women" wire:model.live="trans_men_and_women" class="{{ $field }}">
                            <option value="">{{ __('page_terreiros_create.select_option') }}</option>
                            <option value="sim, usam sempre">{{ __('page_terreiros_create.trans_clothes_always') }}</option>
                            <option value="usam apenas nas funções internas">{{ __('page_terreiros_create.trans_clothes_internal_only') }}</option>
                            <option value="não">{{ __('page_terreiros_create.trans_clothes_no') }}</option>
                        </select>
                        @error('trans_men_and_women') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="name_gender" class="{{ $label }}">{{ __('page_terreiros_create.label_name_gender') }}</label>
                        <select id="name_gender" wire:model.live="name_gender" class="{{ $field }}">
                            <option value="">{{ __('page_terreiros_create.select_option') }}</option>
                            <option value="sim">{{ __('page_terreiros_create.option_yes') }}</option>
                            <option value="não">{{ __('page_terreiros_create.option_no') }}</option>
                        </select>
                        @error('name_gender') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="fully_welcomes" class="{{ $label }}">{{ __('page_terreiros_create.label_fully_welcomes') }}</label>
                        <select id="fully_welcomes" wire:model.live="fully_welcomes" class="{{ $field }}">
                            <option value="">{{ __('page_terreiros_create.select_option') }}</option>
                            <option value="acolhe plenamente">{{ __('page_terreiros_create.welcomes_fully') }}</option>
                            <option value="acolhe parcialmente">{{ __('page_terreiros_create.welcomes_partially') }}</option>
                            <option value="não acolhe">{{ __('page_terreiros_create.welcomes_not') }}</option>
                            <option value="rejeita totalmente">{{ __('page_terreiros_create.welcomes_rejects') }}</option>
                        </select>
                        @error('fully_welcomes') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="respect_for_trans_people" class="{{ $label }}">{{ __('page_terreiros_create.label_respect_for_trans_people') }}</label>
                        <select id="respect_for_trans_people" wire:model.live="respect_for_trans_people" class="{{ $field }}">
                            <option value="">{{ __('page_terreiros_create.select_option') }}</option>
                            <option value="sim, tem">{{ __('page_terreiros_create.respect_yes_has') }}</option>
                            <option value="sim, começou recentemente">{{ __('page_terreiros_create.respect_yes_recently') }}</option>
                            <option value="não, não tem">{{ __('page_terreiros_create.respect_no_has_not') }}</option>
                            <option value="não, mas precisamos de apoio para implementar">{{ __('page_terreiros_create.respect_no_need_support') }}</option>
                        </select>
                        @error('respect_for_trans_people') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="suffered_aggregation" class="{{ $label }}">{{ __('page_terreiros_create.label_suffered_aggregation') }}</label>
                        <select id="suffered_aggregation" wire:model.live="suffered_aggregation" class="{{ $field }}">
                            <option value="">{{ __('page_terreiros_create.select_option') }}</option>
                            <option value="sim">{{ __('page_terreiros_create.option_yes') }}</option>
                            <option value="não">{{ __('page_terreiros_create.option_no') }}</option>
                        </select>
                        @error('suffered_aggregation') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="inclusion_of_the_name_of_the_land" class="{{ $label }}">{{ __('page_terreiros_create.label_inclusion_of_the_name_of_the_land') }}</label>
                        <select id="inclusion_of_the_name_of_the_land" wire:model.live="inclusion_of_the_name_of_the_land" class="{{ $field }}">
                            <option value="">{{ __('page_terreiros_create.select_option') }}</option>
                            <option value="Sim, eu autorizo que nosso terreiro faça parte da listagem de terreiros trans-inclusivos">{{ __('page_terreiros_create.inclusion_authorize_label') }}</option>
                            <option value="não, eu não autorizo que nosso terreiro faça parte da listagem de terreiros trans-inclusivos">{{ __('page_terreiros_create.inclusion_not_authorize_label') }}</option>
                        </select>
                        @error('inclusion_of_the_name_of_the_land') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="suggestion_id" class="{{ $label }}">{{ __('page_terreiros_create.label_suggestion') }}</label>
                        <select id="suggestion_id" wire:model.live="suggestion_id" class="{{ $field }}">
                            <option value="">{{ __('page_terreiros_create.select_suggestion') }}</option>
                            @foreach ($suggestions as $suggestion)<option value="{{ $suggestion->id }}">{{ $suggestion->name }}</option>@endforeach
                        </select>
                        @error('suggestion_id') <p class="{{ $err }}">{{ $message }}</p> @enderror
                    </div>
                    @if ($showField)
                        <div class="sm:col-span-2">
                            <label for="suggestion_text" class="{{ $label }}">{{ __('page_terreiros_create.label_suggestion_text') }}</label>
                            <textarea id="suggestion_text" wire:model.live="suggestion_text" rows="3" class="{{ $field }}"></textarea>
                            @error('suggestion_text') <p class="{{ $err }}">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>
            </fieldset>

            <div class="flex justify-between">
                <button type="button" wire:click="previousStep" class="rounded-full border border-slate-300 px-6 py-3 font-medium text-slate-600 transition hover:bg-slate-50">{{ __('page_terreiros_create.previous_step') }}</button>
                <button type="submit" wire:loading.attr="disabled" wire:target="store"
                        class="rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-7 py-3 font-semibold text-white shadow-md transition hover:brightness-110 disabled:opacity-60">
                    <span wire:loading.remove wire:target="store">{{ __('page_terreiros_create.submit_registration') }}</span>
                    <span wire:loading wire:target="store">{{ __('Loading...') }}</span>
                </button>
            </div>
        @endif
    </form>
</div>
