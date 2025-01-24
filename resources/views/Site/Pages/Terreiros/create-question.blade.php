@extends('Site.layouts.app')

@section('content')
    <div class="row mt-5">
        <div class="col mt-5 text-center">
            <h2>Cadastro de Terreiros inclusivos</h2>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col">
            <form action="{{ route('terreiros.question.store', $id) }}" method="post" id="form-terreiros-question" autocomplete="off">
                @csrf
                <input type="hidden" name="terreiro_id" id="terreiro_id" value="{{ $id }}" />

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="type_people_id">Qual a identidade de gênero da liderança do terreiro?</label>
                            <select name="type_people_id" id="type_people_id" class="form-control">
                                <option selected disabled>Selecione a identidade de gênero</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="number_of_children_of_saint">Quantos filhos de santo o terreiro tem?</label>
                            <input type="number" class="form-control" name="number_of_children_of_saint" id="number_of_children_of_saint" autocomplete="off" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12 mt-4">
                            <label for="number_of_children_of_saint_trans">Quantas pessoas trans/travestis são filhos nesse terreiro?</label>
                            <input type="number" class="form-control" name="number_of_children_of_saint_trans" id="number_of_children_of_saint_trans" autocomplete="off" required />
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="trans_men_and_women">As pessoas trans do terreiro usam roupas segundo o gênero que se identificam? Ex. mulheres trans usam saia? Homens trans usam calça?</label>
                            <select name="trans_men_and_women" id="trans_men_and_women" class="form-control">
                                <option selected disabled>Selecione a opção</option>
                                <option value="sim, usam sempre">sim, usam sempre</option>
                                <option value="usam apenas nas funções internas">usam apenas nas funções internas</option>
                                <option value="não">não</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="name_gender">As pessoas trans do terreiro são chamadas pelo nome e gênero que desejam?</label>
                            <select name="name_gender" id="name_gender" class="form-control">
                                <option selected disabled>Selecione a opção</option>
                                <option value="sim">sim</option>
                                <option value="não">não</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="fully_welcomes">A família espiritual acolhe integralmente as pessoas trans do terreiro ou a liderança ainda precisa mediar as relações?</label>
                            <select name="fully_welcomes" id="fully_welcomes" class="form-control">
                                <option selected disabled>Selecione a opção</option>
                                <option value="acolhe plenamente">acolhe plenamente</option>
                                <option value="acolhe parcialmente">acolhe parcialmente</option>
                                <option value="não acolhe">não acolhe</option>
                                <option value="rejeita totalmente">rejeita totalmente</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="respect_for_trans_people">O terreiro fez alguma ação de conscientização da necessidade de acolhimento respeitoso de pessoas trans em suas dependências?</label>
                            <select name="respect_for_trans_people" id="respect_for_trans_people" class="form-control">
                                <option selected disabled>Selecione a opção</option>
                                <option value="sim, tem">sim, tem</option>
                                <option value="sim, começou recentemente">sim, começou recentemente</option>
                                <option value="não, não tem">não, não tem</option>
                                <option value="não, mas precisamos de apoio para implementar">não, mas precisamos de apoio para implementar</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="suffered_aggregation">A liderança e as pessoas trans do terreiro foram hostilizadas quando os demais terreiros souberam que essas pessoas são respeitadas na casa?</label>
                            <select name="suffered_aggregation" id="suffered_aggregation" class="form-control">
                                <option selected disabled>Selecione a opção</option>
                                <option value="sim">sim</option>
                                <option value="não">não</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="inclusion_of_the_name_of_the_land">Podemos incluir o nome e o contato do seu terreiro na lista de indicações de casas trans-inclusivas para Orientar</label>
                            <select name="inclusion_of_the_name_of_the_land" id="inclusion_of_the_name_of_the_land" class="form-control">
                                <option selected disabled>Selecione a opção</option>
                                <option value="Sim, eu autorizo que nosso terreiro faça parte da listagem de terreiros trans-inclusivos">Sim, eu autorizo que nosso terreiro faça parte da listagem de terreiros trans-inclusivos</option>
                                <option value="não, eu não autorizo que nosso terreiro faça parte da listagem de terreiros trans-inclusivos">não, eu não autorizo que nosso terreiro faça parte da listagem de terreiros trans-inclusivos</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="sugestion_id">Sugestão</label>
                            <select name="sugestion_id" id="sugestion_id" class="form-control mt-4">
                                <option selected disabled>Selecione a sugestão</option>
                            </select>
                        </div>
                    </div>

                    <div class="row" id="suggestionText" style="display: none;">
                        <div class="col-12 col-md-12">
                            <label for="suggestion_text">Sua sugestão</label>
                            <textarea name="suggestion_text" id="suggestion_text" class="form-control" autocomplete="off"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Responder</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('/assets/js/type_peoples/typePeoples.js') }}"></script>
    <script src="{{ asset('/assets/js/suggestions/listSuggestions.js') }}"></script>
    <script src="{{ asset('/assets/js/terreiros/createTerreiroQuestion.js') }}"></script>
@stop
