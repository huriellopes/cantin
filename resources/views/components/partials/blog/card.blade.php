@props(['post'])

<article class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
    @if (! empty($post->main_image))
        <img src="{{ asset("/storage/{$post->main_image}") }}" alt="{{ $post->title }}" class="h-44 w-full object-cover" loading="lazy">
    @else
        <div class="h-44 w-full bg-gradient-to-br from-violet-200 to-pink-200"></div>
    @endif
    <div class="flex flex-1 flex-col p-5">
        <span class="w-fit rounded-full bg-violet-50 px-3 py-1 text-xs font-medium text-violet-700">{{ $post->category?->name }}</span>
        <h3 class="mt-3 text-lg font-bold text-slate-800">{{ $post->title }}</h3>
        <p class="mt-2 flex-1 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 100) }}</p>
        <div class="mt-4 flex items-center justify-between">
            <small class="text-xs text-slate-400">{{ $post->published_at?->format('d/m/Y') }}</small>
            <a href="{{ route('site.blog.show', $post->slug) }}" wire:navigate class="text-sm font-semibold text-violet-600 hover:underline">{{ __('Read more...') }}</a>
        </div>
    </div>
</article>
