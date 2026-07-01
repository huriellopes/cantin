<div class="mx-auto max-w-4xl px-6 py-16">
    @if (! empty($page))
        <h1 class="text-3xl font-extrabold text-slate-800 sm:text-4xl">{{ $page->name }}</h1>
        <div class="mt-6 space-y-4 text-slate-600 [&_h2]:mt-8 [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-slate-800 [&_h3]:mt-6 [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-slate-800 [&_ul]:list-disc [&_ul]:pl-6 [&_a]:text-violet-600">
            {!! $page->content !!}
        </div>
    @else
        <header class="text-center">
            <span class="inline-block rounded-full bg-violet-100 px-4 py-1 text-sm font-medium text-violet-700">{{ __('page_about.badge') }}</span>
            <h1 class="mt-4 text-3xl font-extrabold text-slate-800 sm:text-4xl">{{ __('page_about.title') }}</h1>
        </header>

        <div class="mt-10 grid gap-10 lg:grid-cols-3">
            <article class="space-y-6 text-slate-600 lg:col-span-2">
                <p>
                    {{ __('page_about.intro') }}
                </p>
                <h2 class="text-2xl font-bold text-slate-800">{{ __('page_about.origin_title') }}</h2>
                <p>
                    {!! __('page_about.origin_text') !!}
                </p>
            </article>
            <div class="lg:col-span-1">
                <img src="{{ $image }}" alt="CaNTIn" class="w-full rounded-3xl object-cover shadow-lg" loading="lazy" decoding="async" />
            </div>
        </div>

        <div class="mt-10 space-y-6 text-slate-600">
            <h2 class="text-2xl font-bold text-slate-800">{{ __('page_about.contributions_title') }}</h2>
            <p>
                {!! __('page_about.contributions_text') !!}
            </p>
            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ([
                    [__('page_about.card_registration_title'), __('page_about.card_registration_text')],
                    [__('page_about.card_search_title'), __('page_about.card_search_text')],
                    [__('page_about.card_network_title'), __('page_about.card_network_text')],
                ] as [$t, $d])
                    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                        <h3 class="font-semibold text-violet-700">{{ $t }}</h3>
                        <p class="mt-1 text-sm text-slate-600">{{ $d }}</p>
                    </div>
                @endforeach
            </div>

            <h2 class="text-2xl font-bold text-slate-800">{{ __('page_about.movement_title') }}</h2>
            <p>
                {!! __('page_about.movement_text') !!}
            </p>
        </div>

        <div class="mt-12 rounded-3xl bg-gradient-to-r from-violet-600 to-pink-500 px-8 py-10 text-center text-white">
            <h3 class="text-2xl font-bold">{{ __('page_about.cta_title') }}</h3>
            <p class="mx-auto mt-2 max-w-2xl text-white/90">{{ __('page_about.cta_text') }}</p>
            <a href="{{ route('site.terreiros.create') }}" wire:navigate class="mt-6 inline-block rounded-full bg-white px-7 py-3 font-semibold text-violet-700 shadow-lg transition hover:scale-105">{{ __('page_about.cta_button') }}</a>
        </div>
    @endif
</div>
