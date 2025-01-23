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

    <div class="row mt-4">
        <div class="col-4">
            <form action="{{ route('search.terreiros') }}" method="GET" id="form-search-terreiros">
                <div class="form-group">
                    <div class="row">
                        <div class="input-group mb-3">
                            <select name="uf" id="uf" class="form-control" aria-label="Selecione o estado"
                                    aria-describedby="button-addon2">
                                <option value selected disabled>{{ __('Select a state') }}</option>
                                <option value="todos">{{ __('All the terreiros') }}</option>
                                @foreach ($states as $uf)
                                    <option
                                        value="{{ $uf->id }}" {{ (int) request()->get('uf') == $uf->id ? 'selected' : '' }}>{{ $uf->description }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2-terreiro">{{ __('Search') }}</button>
                        </div>
                    </div>
                </div>
            </form>
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
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if (!empty($terreiros) && count($terreiros) > 0)
                    @foreach($terreiros as $terreiro)
                        <tr>
                            <td>{{ $terreiro->id }}</td>
                            <td>{{ $terreiro->name }}</td>
                            <td>{{ $terreiro->nation->nation }}</td>
                            <td>{{ $terreiro->phone }}</td>
                            <td>{{ $terreiro->leadership_orunko }}</td>
                            <td>{{ \Carbon\Carbon::parse($terreiro->fundationed_at)->format('d/m/Y') }}</td>
                            <td>{{ $terreiro->color_of_leadership }}</td>
                            <td>{{ $terreiro->address->state->description }}</td>
                            <td>{{ $terreiro->address->city->city_name }}</td>
                            <td>
                                <a class="btn btn-primary" data-bs-toggle="collapse"
                                   href="#collapseExample-{{$terreiro->id}}" role="button" aria-expanded="false"
                                   aria-controls="collapseExample">
                                    Detalhes
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="200">
                                <div class="collapse" id="collapseExample-{{$terreiro->id}}">
                                    <div class="card card-body">
                                        <table class="table table-striped">
                                            <tr>
                                                <td>Endereço</td>
                                                <td>{{ $terreiro->address->address }}, {{ $terreiro->address->number }}
                                                    , {{ $terreiro->address->neighborhood }}{{ !empty($terreiro->address->complement) ? ','. $terreiro->address->complement : '' }}
                                                    , {{ $terreiro->address->zipcode }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="200" class="text-center">Nenhum Terreiro Encontrado</td>
                    </tr>
                @endif
                </tbody>
            </table>
            Total de {{ $terreiros->total() }} terreiros encontrados
            {{ $terreiros->appends(request()->input())->links() }}
        </div>
    </div>
@stop

@section('js')
    <script>
        let formSearch = document.getElementById('form-search-terreiros')
        let btnSearch = document.getElementById('button-addon2-terreiro')

        formSearch.addEventListener('submit', function (e) {
            e.preventDefault()

            let uf = this.querySelector('#uf').value;

            if (uf === "") {
                btnSearch.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Buscando...'
                btnSearch.setAttribute('disabled', 'disabled')

                setTimeout(function () {
                    btnSearch.innerHTML = 'Buscar'
                    btnSearch.removeAttribute('disabled')
                    notify('error', 'Atenção!', 'Selecione um estado, para fazer a busca!')
                }, 1000)
            }

            if (uf >= 1) {
                btnSearch.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Buscando...'
                btnSearch.setAttribute('disabled', 'disabled')

                window.location.href = `/terreiros?uf=${uf}`
            } else if (uf === 'todos') {
                btnSearch.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Buscando...'
                btnSearch.setAttribute('disabled', 'disabled')

                window.location.href = `/terreiros`
            }
        })
    </script>
{{--        <script src="{{ asset('assets/js/Search/searchTerreiro.js') }}"></script>--}}
@stop
