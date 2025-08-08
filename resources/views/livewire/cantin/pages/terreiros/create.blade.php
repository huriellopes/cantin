<div class="container mt-5">
    <style>
        .steps {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            margin-top: 30px;
        }

        .step {
            display: flex;
            align-items: center;
            margin: 0 10px;
        }

        .step-number {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #ccc;
            color: #fff;
            margin-right: 5px;
        }

        .step.active .step-number {
            background-color: #007bff;
        }

        .spinner {
            display: none; /* Oculta o spinner por padrão */
            border: 2px solid #f3f3f3; /* Cor de fundo */
            border-top: 2px solid #3498db; /* Cor do spinner */
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 2s linear infinite;
        }

        .spinner.active {
            display: inline-block; /* Exibe o spinner durante o carregamento */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <div class="row mt-5">
        <div class="col">
            <div class="steps">
                <div class="step @if ($currentStep >= 1) active @endif">
                    <span class="step-number">1</span>
                    <span class="step-text">Dados do Terreiro</span>
                </div>
                <div class="step @if ($currentStep >= 2) active @endif">
                    <span class="step-number">2</span>
                    <span class="step-text">Perguntas</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col text-center">
            <h2>Cadastro de Terreiros inclusivos</h2>
        </div>
    </div>

    <div class="row mt-3">
        <form wire:submit.prevent="store" class="needs-validation">
            @csrf
            @if($currentStep === 1)
                <fieldset class="form-group border p-3">
                    <legend class="float-none w-auto px-1">Dados do Terreiro</legend>
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <label for="name">Nome do Terreiro</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') border-danger @enderror" wire:model.live="name" />
                            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="nation_terreiro_id">{{ __('Nation') }}</label>
                            <select name="nation_terreiro_id" id="nation_terreiro_id" class="form-control @error('nation_terreiro_id') border-danger @enderror" wire:model.live="nation_terreiro_id">
                                <option selected value="">{{ __('Select the nation') }}</option>
                                @foreach ($nations as $nation)
                                    <option value="{{ $nation->id }}">{{ $nation->name }}</option>
                                @endforeach
                            </select>
                            @error('nation_terreiro_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="phone">Telefone</label>
                            <input type="text" class="form-control @error('phone') border-danger @enderror" name="phone" id="phone" x-mask="(99) 9 9999-9999" wire:model.live="phone" />
                            @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="leadership_orunko">Orukó ou nome da liderança</label>
                            <input type="text" class="form-control @error('leadership_orunko') border-danger @enderror" name="leadership_orunko" id="leadership_orunko" wire:model.live="leadership_orunko" />
                            @error('leadership_orunko') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="color_of_leadership">Cor de pele da liderança</label>
                            <select name="color_of_leadership" id="color_of_leadership" class="form-control @error('color_of_leadership') border-danger @enderror" wire:model.live="color_of_leadership">
                                <option selected value="">Selecione a cor de pele</option>
                                @foreach(config('color-leader.list') as $color => $name)
                                    <option value="{{ $color }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('color_of_leadership') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </fieldset>

                <fieldset class="form-group border p-3 mt-3">
                    <legend class="float-none w-auto px-1">Endereço do Terreiro</legend>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="zipcode" class="form-label">{{ __('Zip Code') }}</label>
                            <div class="input-group">
                                <input type="text" name="zipcode" id="zipcode" class="form-control @error('zipcode') border-danger @enderror" maxlength="9" wire:model.live="zipcode" x-mask="99999-999" />
                                <button class="btn btn-outline-secondary zipcode-search" type="button" id="button-addon2" wire:click="searchZipCode" wire:loading.attr="disabled">
                                    <i class="fa-solid fa-magnifying-glass" id="zipcode-search"></i>
                                    <span wire:loading wire:target="searchZipCode" class="spinner"></span>
                                </button>
                            </div>
                            @error('zipcode') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 col-12 mt-2">
                            <label for="address">{{ __('Address') }}</label>
                            <input type="text" name="address" id="address" class="form-control @error('address') border-danger @enderror" wire:model.live="address" />
                            @error('address') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="complement">{{ __('Complement') }}</label>
                            <input type="text" name="complement" id="complement" class="form-control @error('complement') border-danger @enderror" wire:model.live="complement" />
                            @error('complement') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="neighborhood">{{ __('Neighborhood') }}</label>
                            <input type="text" name="neighborhood" id="neighborhood" class="form-control @error('neighborhood') border-danger @enderror" wire:model.live="neighborhood" />
                            @error('neighborhood') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="state_id">{{ __('State') }}</label>
                            <select name="state_id" id="state_id" class="form-control @error('state_id') border-danger @enderror" wire:model.live="state_id">
                                <option selected value="">{{ __('Select the state') }}</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ $state_id === $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                                @error('state_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="city_id">{{ __('City') }}</label>
                            <select name="city_id" id="city_id" class="form-control @error('city_id') border-danger @enderror" wire:model.live="city_id" wire:loading.attr="disabled" wire:target="state_id">
                                <option selected value="">{{ __('Select the city') }}</option>
                                @if (!empty($cities))
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}" {{ $city_id === $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('city_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </fieldset>

                <div class="form-group">
                    <div class="row mt-4 mb-4">
                        <div class="col">
                            <button type="button" class="btn btn-outline-primary" wire:click="nextStep" wire:loading.attr="disabled">Próxima Etapa</button>
                        </div>
                    </div>
                </div>
            @elseif($currentStep === 2)
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="type_people_id">Qual a identidade de gênero da liderança do terreiro?</label>
                            <select name="type_people_id" id="type_people_id" class="form-control @error('type_people_id') border-danger @enderror" wire:model.live="type_people_id">
                                <option selected value="">Selecione a identidade de gênero</option>
                                @foreach($typePeoples as $typePeople)
                                    <option value="{{ $typePeople->id }}">{{ $typePeople->name }}</option>
                                @endforeach
                            </select>
                            @error('type_people_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="number_of_children_of_saint">Quantos membros ativos o terreiro tem?</label>
                            <input type="number" class="form-control @error('number_of_children_of_saint') border-danger @enderror" name="number_of_children_of_saint" id="number_of_children_of_saint" wire:model.live="number_of_children_of_saint" />
                            @error('number_of_children_of_saint') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12 mt-4">
                            <label for="number_of_children_of_saint_trans">Quantas pessoas trans/travestis são integrantes desse terreiro?</label>
                            <input type="number" class="form-control @error('number_of_children_of_saint_trans') border-danger @enderror" name="number_of_children_of_saint_trans" id="number_of_children_of_saint_trans" wire:model.live="number_of_children_of_saint_trans" />
                            @error('number_of_children_of_saint_trans') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="trans_men_and_women">As pessoas trans do terreiro usam roupas segundo o gênero que se identificam? Ex. mulheres trans usam saia? Homens trans usam calça?</label>
                            <select name="trans_men_and_women" id="trans_men_and_women" class="form-control @error('trans_men_and_women') border-danger @enderror" wire:model.live="trans_men_and_women">
                                <option selected value="">Selecione a opção</option>
                                <option value="sim, usam sempre">sim, usam sempre</option>
                                <option value="usam apenas nas funções internas">usam apenas nas funções internas</option>
                                <option value="não">não</option>
                            </select>
                            @error('trans_men_and_women') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="name_gender">As pessoas trans do terreiro são chamadas pelo nome e gênero que desejam?</label>
                            <select name="name_gender" id="name_gender" class="form-control @error('name_gender') border-danger @enderror" wire:model.live="name_gender">
                                <option selected value="">Selecione a opção</option>
                                <option value="sim">sim</option>
                                <option value="não">não</option>
                            </select>
                            @error('name_gender') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="fully_welcomes">A família espiritual acolhe integralmente as pessoas trans do terreiro ou a liderança ainda precisa mediar as relações?</label>
                            <select name="fully_welcomes" id="fully_welcomes" class="form-control @error('fully_welcomes') border-danger @enderror" wire:model.live="fully_welcomes">
                                <option selected value="">Selecione a opção</option>
                                <option value="acolhe plenamente">acolhe plenamente</option>
                                <option value="acolhe parcialmente">acolhe parcialmente</option>
                                <option value="não acolhe">não acolhe</option>
                                <option value="rejeita totalmente">rejeita totalmente</option>
                            </select>
                            @error('fully_welcomes') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="respect_for_trans_people">O terreiro fez alguma ação de conscientização da necessidade de acolhimento respeitoso de pessoas trans em suas dependências?</label>
                            <select name="respect_for_trans_people" id="respect_for_trans_people" class="form-control @error('respect_for_trans_people') border-danger @enderror" wire:model.live="respect_for_trans_people">
                                <option selected value="">Selecione a opção</option>
                                <option value="sim, tem">sim, tem</option>
                                <option value="sim, começou recentemente">sim, começou recentemente</option>
                                <option value="não, não tem">não, não tem</option>
                                <option value="não, mas precisamos de apoio para implementar">não, mas precisamos de apoio para implementar</option>
                            </select>
                            @error('respect_for_trans_people') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="suffered_aggregation">A liderança e as pessoas trans do terreiro foram hostilizadas quando os demais terreiros souberam que essas pessoas são respeitadas na casa?</label>
                            <select name="suffered_aggregation" id="suffered_aggregation" class="form-control @error('suffered_aggregation') border-danger @enderror" wire:model.live="suffered_aggregation">
                                <option selected value="">Selecione a opção</option>
                                <option value="sim">sim</option>
                                <option value="não">não</option>
                            </select>
                            @error('suffered_aggregation') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="inclusion_of_the_name_of_the_land">Podemos incluir o nome e o contato do seu terreiro na lista de indicações de casas trans-inclusivas para Orientar</label>
                            <select name="inclusion_of_the_name_of_the_land" id="inclusion_of_the_name_of_the_land" class="form-control @error('inclusion_of_the_name_of_the_land') border-danger @enderror" wire:model.live="inclusion_of_the_name_of_the_land">
                                <option selected value="">Selecione a opção</option>
                                <option value="Sim, eu autorizo que nosso terreiro faça parte da listagem de terreiros trans-inclusivos">Sim, eu autorizo que nosso terreiro faça parte da listagem de terreiros trans-inclusivos</option>
                                <option value="não, eu não autorizo que nosso terreiro faça parte da listagem de terreiros trans-inclusivos">não, eu não autorizo que nosso terreiro faça parte da listagem de terreiros trans-inclusivos</option>
                            </select>
                            @error('inclusion_of_the_name_of_the_land') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="sugestion_id">Sugestão</label>
                            <select name="sugestion_id" id="sugestion_id" class="form-control mt-4 @error('sugestion_id') border-danger @enderror" wire:model.live="suggestion_id">
                                <option selected value="">Selecione a sugestão</option>
                                @foreach($suggestions as $suggestion)
                                    <option value="{{ $suggestion->id }}">{{ $suggestion->name }}</option>
                                @endforeach
                            </select>
                            @error('suggestion_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    @if ($showField)
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <label for="suggestion_text">Sua sugestão</label>
                                <textarea name="suggestion_text" id="suggestion_text" class="form-control @error('suggestion_text') border-danger @enderror" wire:model.live="suggestion_text"></textarea>
                                @error('suggestion_text') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-outline-primary" wire:click="previousStep" wire:loading.attr="disabled">
                                Etapa Anterior
                            </button>
                            <button type="submit" class="btn btn-outline-primary" wire:loading.attr="disabled" wire:target="store">
                                Responder
                                <span wire:loading wire:target="store" class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">{{ __('Loading...') }}</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>
