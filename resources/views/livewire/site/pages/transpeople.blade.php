<div class="container mt-4 mb-4">
    <div class="row mt-5">
        <div class="col mt-2">
            <h1 class="text-center">{{ __('Terreiro Trans People Registry') }}</h1>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <form wire:submit.prevent="store" class="needs-validation">
                <div class="form-group">
                    @include('components.partials.creates.address-phone')
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
