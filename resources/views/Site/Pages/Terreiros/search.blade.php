@extends('Site.layouts.app')

@section('css')
    <style>
        footer {
            display: none;
        }
    </style>
@stop

@section('content')
    <div class="row mt-5">
        <div class="col mt-5 text-center">
            <h2>Terreiros inclusivos</h2>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-8">
                <label for="uf" class="col-form-label">Filtro de Estados</label>
                <select name="uf" id="uf" class="form-control">
                    <option value selected disabled>Selecione o estado</option>
                    @foreach ($states as $uf)
                        <option value="{{ $uf->id }}">{{ $uf->description }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <table class="table table-striped" id="table-terreiro">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome do Terreiro</th>
                        <th>Nação da casa</th>
                        <th>Telefone</th>
                        <th>Orunko do dono da casa</th>
                        <th>Fundação da casa</th>
                        <th>Cor de pele da liderança</th>
                        <th>Estado</th>
                        <th>Cidade</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('assets/js/Search/searchTerreiro.js') }}"></script>
@stop
