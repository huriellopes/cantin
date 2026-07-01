@props(['commons'])

@assets
<style>
    .card:not(:first-child) {
        margin-top: .4rem;
    }
    .card-header {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        font-weight: 500;
        cursor: pointer;
    }

    .card-header:hover {
        background-color: #f0f0f0;
        border-color: #ddd;
    }

    .rotate-0 {
        transform: rotate(0deg);
        transition: transform 0.3s ease;
    }

    .rotate-180 {
        transform: rotate(180deg);
        transition: transform 0.3s ease;
    }
</style>
@endassets

<div class="container mb-4">
    <div class="row mt-3">
        <div class="col">
            <h2 class="text-center">{{ __('Frequently Questions') }}</h2>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            @foreach ($commons as $common)
                <div class="card" x-data="{ expanded: false }" wire:key="{{ $common->id }}">
                    <div class="card-header flex items-center" @click="expanded = ! expanded">
                        <span class="font-semibold text-sm">{{ $common->question }}</span>
                        <span class="ms-auto transition-transform" :class="{ 'rotate-180': expanded, 'rotate-0': ! expanded }">
                            @svg('lucide-chevron-down', 'h-5 w-5 text-slate-500')
                        </span>
                    </div>
                    <div
                        class="card-body"
                        x-show="expanded" x-collapse.duration.1000ms
                    >
                        <p class="text-sm">{!! $common->answer !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
