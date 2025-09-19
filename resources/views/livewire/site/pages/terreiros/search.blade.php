<div class="container mt-4">
    <div class="row mt-2">
        <div class="col mt-5 text-center">
            <h2>Terreiros inclusivos</h2>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-5 col-12 col-lg-5">
            <div class="input-group mb-3">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Pesquisar por nome do terreiro ou estado"
                    wire:model.live.debounce.250ms="search"
                    wire:target="search" />
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2" wire:loading wire:target="search">
        <div class="spinner-border" role="status" wire:loading wire:target="search">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span wire:loading wire:target="search">{{ __('Searching terreiros...') }}</span>
    </div>

    <div class="row mt-3" wire:loading.remove wire:target="search">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-striped" id="table-terreiro">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome do Terreiro</th>
                            <th>Nação da casa</th>
                            <th>Telefone</th>
                            <th>Orukó ou nome da liderança</th>
                            <th>Cor de pele da liderança</th>
                            <th>Estado</th>
                            <th>Cidade</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody x-data="{ expanded: null }">
                        @if (!empty($terreiros) && count($terreiros) > 0)
                            @foreach($terreiros as $terreiro)
                                <tr>
                                    <th scope="row">{{ $terreiro->id }}</th>
                                    <td>{{ $terreiro->name }}</td>
                                    <td>{{ $terreiro?->nation->name }}</td>
                                    <td>{{ maskPhone($terreiro->phone) }}</td>
                                    <td>{{ $terreiro->leadership_orunko }}</td>
                                    <td>{{ $terreiro->color_of_leadership }}</td>
                                    <td>{{ $terreiro?->address?->state->abbr }}</td>
                                    <td>{{ $terreiro?->address?->city->name }}</td>
                                    <td>
                                        <a class="btn btn-outline-primary" @click="expanded = (expanded === {{ $terreiro->id }} ? null : {{ $terreiro->id }})">
                                            Detalhes
                                        </a>
                                    </td>
                                </tr>
                                <tr x-show="expanded === {{ $terreiro->id }}" x-collapse.duration.1000ms>
                                    <td colspan="200">
                                        <div class="card card-body">
                                            <table class="table table-striped">
                                                <tr>
                                                    <td>Endereço</td>
                                                    <td>{{ $terreiro->address->address }}
                                                        , {{ $terreiro->address->neighborhood }}{{ !empty($terreiro->address->complement) ? ','. $terreiro->address->complement : '' }}
                                                        , {{ $terreiro?->address?->city->name }}/{{ $terreiro?->address?->state?->abbr }}
                                                        , {{ $terreiro->address->zipcode }}</td>
                                                </tr>
                                            </table>
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
            {{ $terreiros->links() }}
        </div>
    </div>

</div>
