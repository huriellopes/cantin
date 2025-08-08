<div class="container mt-4">
    <style>
        .spinner {
            display: none; /* Oculta o spinner por padrão */
            border: 2px solid #f3f3f3; /* Cor de fundo */
            border-top: 2px solid #3498db; /* Cor do spinner */
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 2s linear infinite;
        }

        .bi-search.hidden {
            display: none;
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
        <div class="col mt-2">
            <h1 class="text-center">{{ __('Terreiro Trans People Registry') }}</h1>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <form wire:submit.prevent="store" class="needs-validation">
                <div class="form-group">
                    @include('livewire.cantin.pages.partials.address-phone')
                </div>

                <div class="form-group">
                    <div class="row mt-4">
                        <div class="col">
                            <button type="submit" class="btn btn-outline-primary" wire:loading.attr="disabled" wire:target="store">
                                {{ __('Register') }}
                                <span wire:loading wire:target="store" class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">{{ __('Loading...') }}</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
