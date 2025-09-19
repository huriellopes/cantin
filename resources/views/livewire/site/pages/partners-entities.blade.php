<div class="container mt-4">
    <div class="row mt-5">
        <div class="col mt-2">
            <h1 class="text-center">{{ __('Registration of Partner Entity') }}</h1>
        </div>
    </div>

    <div class="row mt-3 mb-4">
        <div class="col">
            <form wire:submit.prevent="store" class="needs-validation">
                <div class="form-group">
                    @include('components.partials.creates.address-phone')

                    <div class="row">
                        <div class="col-12 col-md-12">
                            <label for="activity_carried_out" class="form-label">Atividade Desenvolvida</label>
                            <textarea name="activity_carried_out" id="activity_carried_out" cols="30" rows="10" class="form-control @error('activity_carried_out') border-danger @enderror" autocomplete="off" wire:model.live="activity_carried_out"></textarea>
                            @error('activity_carried_out') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
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
