@assets
<style>
    .banner-topo {
        background-image: url({{ $image }});
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
@endassets

<div>
    <div class="banner-topo">
        <div class="container">
            <div class="row content-banner">
                <div class="col">
                    <h1 class="text-white">{{ __('National Register of') }} <br> {{ __('Inclusive Terreiros') }}</h1>
                    <a href="{{ route('site.terreiros.create') }}" class="btn btn-lg btn-outline-primary" wire:navigate wire:target.attr="disabled">
                        {{ __('Register your Terreiro') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (!empty($partners) && count($partners) > 0)
        <x-partials.home.partners :partners="$partners" />
    @endif

    @if (!empty($commons))
        <x-partials.home.commons-questions :commons="$commons" />
    @endif
</div>
