@extends('Site.layouts.app')

@section('content')
    <div class="row mt-5">
        <div class="col mt-5 text-center">
            <h2>Cadastro de Terreiros inclusivos</h2>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <form action="{{ route('terreiros.store') }}" method="post" id="form-terreiros" autocomplete="off">
                @csrf
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="name">Nome do Terreiro</label>
                            <input type="text" name="name" id="name" class="form-control" required autocomplete="off" />
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="nation_terreiro_id">Nação</label>
                            <select name="nation_terreiro_id" id="nation_terreiro_id" class="form-control" required>
                                <option selected disabled>Selecione a nação</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="phone">Telefone</label>
                            <input type="text" class="form-control" name="phone" id="phone" required autocomplete="off" />
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="fundationed_at">Fundação da Terreiro</label>
                            <input type="date" class="form-control" name="fundationed_at" id="fundationed_at" required autocomplete="off" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="leadership_orunko">Orunko da liderança</label>
                            <input type="text" class="form-control" name="leadership_orunko" id="leadership_orunko" required autocomplete="off" />
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="color_of_leadership">Cor de pele da liderança</label>
                            <select name="color_of_leadership" id="color_of_leadership" class="form-control" required>
                                <option selected disabled>Selecione a cor de pele</option>
                                <option value="amarelo">Amarelo</option>
                                <option value="branco">Branco</option>
                                <option value="indígena">Indígena</option>
                                <option value="pardo">Pardo</option>
                                <option value="preto">Preto</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="cep">CEP</label>
                            <input type="text" id="cep" name="zipcode" class="form-control" autocomplete="off" required maxlength="8" />
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="address">Endereço</label>
                            <input type="text" name="address" id="address" class="form-control" required autocomplete="off" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="number">Número</label>
                            <input type="text" id="number" name="number" class="form-control" required autocomplete="off" />
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="complement">Complemento</label>
                            <input type="text" name="complement" id="complement" class="form-control" autocomplete="off" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-12">
                            <label for="state">Estado</label>
                            <select name="state_id" id="state" class="form-control" required>
                                <option selected disabled>Selecione o estado</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="city">Cidade</label>
                            <select name="city_id" id="city" class="form-control" required>
                                <option selected disabled>Selecione a cidade</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="neighborhood">Bairro</label>
                            <input type="text" name="neighborhood" id="neighborhood" class="form-control" autocomplete="off" required />
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-outline-primary">Continuar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('/assets/js/nations/nations.js') }}"></script>
    <script src="{{ asset('/assets/js/cities/liststate.js') }}"></script>
    <script src="{{ asset('/assets/js/cities/getCep.js') }}"></script>
    <script src="{{ asset('/assets/js/terreiros/createTerreiros.js') }}"></script>
@stop
