<div>
    <style>
        .banner-topo {
            background-image: url({{ asset('/assets/images/background-outro.png') }});
            background-repeat: no-repeat;
            background-size: cover;
            background-position: top center;
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .banner-topo .content-banner {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }
    </style>

    <div class="banner-topo">
        <div class="container">
            <div class="row content-banner">
                <div class="col">
                    <h1 class="text-white">{{ __('National Register of') }} <br> {{ __('Trans-Inclusive Terreiros') }}</h1>
                    <a href="{{ route('site.terreiros.create') }}" class="btn btn-lg btn-outline-primary" wire:navigate>{{ __('Register your Terreiro') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-4">
        <div class="row mt-3">
            <div class="col">
                <h2 class="text-center">{{ __('Frequently Questions') }}</h2>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="accordion accordion-flush border" id="accordionFlushExample">
                    @if (count($commons) > 0)
                        @foreach ($commons as $common)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapse{{ $common->id }}" aria-expanded="false"
                                            aria-controls="flush-collapse{{ $common->id }}">
                                        {{ $common->question }}
                                    </button>
                                </h2>
                                <div id="flush-collapse{{ $common->id }}" class="accordion-collapse collapse"
                                     data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        {!! $common->answer !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('livewire.cantin.pages.contact')
</div>
