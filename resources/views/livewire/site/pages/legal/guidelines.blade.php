<div class="mx-auto max-w-4xl px-6 py-16">
    @if (! empty($page))
        <header>
            <h1 class="text-3xl font-extrabold text-slate-800 sm:text-4xl">{{ $page->name }}</h1>
        </header>
        <div class="mt-8 space-y-6 text-slate-600 [&_h2]:mt-8 [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-slate-800 [&_ul]:list-disc [&_ul]:space-y-1 [&_ul]:pl-6 [&_a]:text-violet-600">
            {!! $page->content !!}
        </div>
    @else
    <header>
        <span class="inline-block rounded-full bg-violet-100 px-4 py-1 text-sm font-medium text-violet-700">{{ __('page_guidelines.badge') }}</span>
        <h1 class="mt-4 text-3xl font-extrabold text-slate-800 sm:text-4xl">{{ __('page_guidelines.title') }}</h1>
        <p class="mt-2 text-sm text-slate-500">{{ __('page_guidelines.last_updated') }} {{ now()->format('d/m/Y') }}</p>
    </header>

    <div class="mt-8 space-y-6 text-slate-600 [&_h2]:mt-8 [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-slate-800 [&_ul]:list-disc [&_ul]:space-y-1 [&_ul]:pl-6 [&_a]:text-violet-600">
        <p>
            {{ __('page_guidelines.intro') }}
        </p>

        <h2>{{ __('page_guidelines.section_respect_title') }}</h2>
        <ul>
            <li>{{ __('page_guidelines.section_respect_item_1') }}</li>
            <li>{{ __('page_guidelines.section_respect_item_2') }}</li>
        </ul>

        <h2>{{ __('page_guidelines.section_registration_title') }}</h2>
        <ul>
            <li>{{ __('page_guidelines.section_registration_item_1') }}</li>
            <li>{{ __('page_guidelines.section_registration_item_2') }}</li>
            <li>{{ __('page_guidelines.section_registration_item_3') }}</li>
        </ul>

        <h2>{{ __('page_guidelines.section_content_title') }}</h2>
        <ul>
            <li>{{ __('page_guidelines.section_content_item_1') }}</li>
            <li>{{ __('page_guidelines.section_content_item_2') }}</li>
        </ul>

        <h2>{{ __('page_guidelines.section_usage_title') }}</h2>
        <ul>
            <li>{{ __('page_guidelines.section_usage_item_1') }}</li>
            <li>{{ __('page_guidelines.section_usage_item_2') }}</li>
        </ul>

        <h2>{{ __('page_guidelines.section_moderation_title') }}</h2>
        <p>
            {{ __('page_guidelines.section_moderation_text_before') }}
            <a href="mailto:seggvg@gmail.com">seggvg@gmail.com</a>.
        </p>
    </div>
    @endif
</div>
