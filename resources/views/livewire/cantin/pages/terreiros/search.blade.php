<div class="container mt-4">
    <div class="row mt-2">
        <div class="col mt-5 text-center">
            <h2>Terreiros inclusivos</h2>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-5 col-12 col-lg-5">
            <div class="input-group mb-3">
                <select name="uf" id="uf" class="form-control" aria-label="Selecione o estado"
                        aria-describedby="button-addon2" wire:model.live="search" wire:target="search">
                    <option selected>{{ __('Select a state') }}</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->slug }}">
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2" wire:loading wire:target="search">
        <div class="spinner-border" role="status" wire:loading wire:target="search">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span wire:loading wire:target="search">{{ __('Searching terreiros...') }}</span>
    </div>

    <div class="row mt-3" wire:loading.remove="search" wire:target="search">
        <div class="col">
            <div class="table-responsive">
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
                                <th scope="row">{{ $terreiro->id }}</th>
                                <td>{{ $terreiro->name }}</td>
                                <td>{{ $terreiro->nation->name }}</td>
                                <td>{{ maskPhone($terreiro->phone) }}</td>
                                <td>{{ $terreiro->leadership_orunko }}</td>
                                <td>{{ \Carbon\Carbon::parse($terreiro->fundationed_at)->format('d/m/Y') }}</td>
                                <td>{{ $terreiro->color_of_leadership }}</td>
                                <td>{{ $terreiro->address->state->name }}</td>
                                <td>{{ $terreiro->address->city->name }}</td>
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
            </div>
            {{ $terreiros->appends(request()->input())->links() }}
        </div>
    </div>

</div>
