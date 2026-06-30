<div class="mx-auto max-w-4xl px-6 py-16">
    <header>
        <span class="inline-block rounded-full bg-violet-100 px-4 py-1 text-sm font-medium text-violet-700">{{ __('page_privacy.badge') }}</span>
        <h1 class="mt-4 text-3xl font-extrabold text-slate-800 sm:text-4xl">{{ __('page_privacy.title') }}</h1>
        <p class="mt-2 text-sm text-slate-500">{{ __('page_privacy.last_updated', ['date' => now()->format('d/m/Y')]) }}</p>
    </header>

    <div class="mt-8 space-y-6 text-slate-600 [&_h2]:mt-8 [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-slate-800 [&_ul]:list-disc [&_ul]:space-y-1 [&_ul]:pl-6 [&_a]:text-violet-600">
        <p>
            {!! __('page_privacy.intro') !!}
        </p>

        <h2>{{ __('page_privacy.controller_title') }}</h2>
        <p>
            {!! __('page_privacy.controller_text') !!}
        </p>

        <h2>{{ __('page_privacy.data_title') }}</h2>
        <ul>
            <li>{!! __('page_privacy.data_registration') !!}</li>
            <li>{!! __('page_privacy.data_navigation') !!}</li>
            <li>{!! __('page_privacy.data_cookies') !!}</li>
        </ul>

        <h2>{{ __('page_privacy.purpose_title') }}</h2>
        <ul>
            <li>{{ __('page_privacy.purpose_1') }}</li>
            <li>{{ __('page_privacy.purpose_2') }}</li>
            <li>{{ __('page_privacy.purpose_3') }}</li>
        </ul>

        <h2>{{ __('page_privacy.cookies_title') }}</h2>
        <p>
            {!! __('page_privacy.cookies_text') !!}
        </p>

        <h2>{{ __('page_privacy.sharing_title') }}</h2>
        <p>
            {!! __('page_privacy.sharing_text') !!}
        </p>

        <h2>{{ __('page_privacy.rights_title') }}</h2>
        <p>{{ __('page_privacy.rights_intro') }}</p>
        <ul>
            <li>{{ __('page_privacy.rights_1') }}</li>
            <li>{{ __('page_privacy.rights_2') }}</li>
            <li>{{ __('page_privacy.rights_3') }}</li>
            <li>{{ __('page_privacy.rights_4') }}</li>
            <li>{{ __('page_privacy.rights_5') }}</li>
        </ul>

        <h2>{{ __('page_privacy.retention_title') }}</h2>
        <p>
            {{ __('page_privacy.retention_text') }}
        </p>

        <h2>{{ __('page_privacy.changes_title') }}</h2>
        <p>
            {{ __('page_privacy.changes_text') }}
        </p>

        <p class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">
            {{ __('page_privacy.disclaimer') }}
        </p>
    </div>
</div>
