@extends('Site.layouts.app')

@section('content')
    <div class="row mt-5">
        <div class="col mt-2">
            <h1 class="text-center">Cadastro de Pessoas Trans</h1>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <form action="{{ route('transpeople.store') }}" method="POST" id="formTransPeople" autocomplete="off">
                @csrf
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" id="name" class="form-control" required autocomplete="off" />
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control" required autocomplete="off" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="phone" class="form-label">Telefone</label>
                            <input type="text" name="phone" id="phone" class="form-control" required autocomplete="off" />
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" name="zipcode" id="cep" class="form-control" required autocomplete="off" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="address" class="form-label">Endereço</label>
                            <input type="text" name="address" id="address" class="form-control" required autocomplete="off" />
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="number" class="form-label">Número</label>
                            <input type="text" name="number" id="number" class="form-control" required autocomplete="off" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="complement" class="form-label">Complemento</label>
                            <input type="text" name="complement" id="complement" class="form-control" autocomplete="off" />
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="neighborhood" class="form-label">Bairro</label>
                            <input type="text" name="neighborhood" id="neighborhood" class="form-control" required autocomplete="off" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="statec" class="form-label">Estado</label>
                            <select name="state_id" id="state" class="form-control" required>
                                <option selected disabled>Selecione o estado</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="city" class="form-label">Cidade</label>
                            <select name="city_id" id="city" class="form-control" required>
                                <option selected disabled>Selecione a cidade</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row mt-4">
                        <div class="col">
                            <button type="submit" class="btn btn-outline-primary">Cadastrar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('/assets/js/cities/liststate.js') }}"></script>
    <script src="{{ asset('/assets/js/cities/getCep.js') }}"></script>
    <script src="{{ asset('/assets/js/transPeoples/createTransPeople.js') }}"></script>
@stop
