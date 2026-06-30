<div class="mx-auto max-w-4xl px-6 py-16">
    <h1 class="text-center text-3xl font-extrabold text-slate-800 sm:text-4xl">
        {{ str($page->name)->ucfirst() }}
    </h1>
    <div class="mt-8 space-y-4 text-slate-600 [&_a]:text-violet-600 [&_h2]:mt-6 [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-slate-800 [&_ul]:list-disc [&_ul]:pl-6">
        {!! $page->content !!}
    </div>
</div>
