@assets
<style>
    .types-list {
        list-style: none;
        padding-left: 0;
    }

    .types-list li {
        margin-bottom: 8px;
    }

    .types-list a {
        text-decoration: none;
        color: #495057;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .types-list a:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .types-list .badge {
        background-color: #e9ecef;
        color: #495057;
    }
</style>
@endassets

<div class="container mt-5 mb-5">
    <div class="row mt-4">
        <div class="col-md-3 col-12 mt-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 d-flex align-items-center">
                        <span>Pesquisar</span>
                    </h5>
                </div>
                <div class="card-body">
                    <input type="text" name="search" id="search" wire:model.live="search" class="form-control" placeholder="Pesquisar..." />
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 d-flex align-items-center">
                        <span>Tipos de Links</span>
                        @if($selectedLinkType)
                            <button type="button" wire:click="clearLinkType" wire:loading.attr="disabled" wire:target="clearLinkType" class="btn btn-sm btn-outline-dark ms-auto">
                                Limpar
                                <span wire:loading wire:target="clearLinkType" class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">{{ __('Loading...') }}</span>
                                </span>
                            </button>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="types-list">
                        @foreach ($types as $type)
                            <li>
                                <a href="?type={{ $type->slug }}" wire:navigate wire:click="selectCategory({{ $type->slug }})"
                                   class="text-decoration-none {{ $selectedLinkType === $type->slug ? 'fw-bold text-primary' : '' }}">
                                    {{ $type->name }} <span class="badge rounded-pill">( {{ $type->links->count() }} )</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9 col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 d-flex align-items-center">
                        <span>
                            Links Externos
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    @if ($links->count() > 0)
                        @foreach ($links as $link)
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $link->title }}</h5>
                                    <p class="card-text">{{ $link->description }}</p>
                                    <a href="{{ $link->url }}" target="_blank" class="btn btn-primary">Acessar</a>
                                </div>
                                <div class="card-footer d-flex justify-content-between items-center gap-4">
                                    <small class="text-muted">Tipo: {{ $link->type?->name }}</small>
                                    <small class="text-muted">Criado em: {{ $link->created_at->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>Nenhum link encontrado.</p>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                {{ $links->links() }}
            </div>
        </div>

    </div>
</div>
