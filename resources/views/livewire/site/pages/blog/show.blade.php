<div>
    @push('jsonld')
        {!! $articleJsonLd !!}
    @endpush

    {{-- Cabeçalho --}}
    <header class="bg-gradient-to-br from-violet-50 to-pink-50">
        <div class="mx-auto max-w-3xl px-6 py-14 text-center">
            <span class="inline-block rounded-full bg-violet-100 px-3 py-1 text-sm font-medium text-violet-700">{{ $post->category?->name }}</span>
            <h1 class="mt-4 text-3xl font-extrabold text-slate-800 sm:text-4xl">{{ $post->title }}</h1>
            <div class="mt-6 flex items-center justify-center gap-3">
                <img src="{{ asset('/assets/images/avatar.png') }}" alt="{{ $post->user?->name }}" class="h-12 w-12 rounded-full">
                <div class="text-left">
                    <p class="font-medium text-slate-700">{{ $post->user?->name }}</p>
                    <p class="text-xs text-slate-500">{{ $post->published_at?->format('d/m/Y') }} · {{ readingTime($post->content) }}</p>
                </div>
            </div>
        </div>
    </header>

    <article class="mx-auto max-w-3xl px-6 py-12">
        <div class="space-y-4 text-lg leading-relaxed text-slate-700 [&_a]:text-violet-600 [&_h2]:mt-8 [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-slate-800 [&_h3]:mt-6 [&_h3]:text-xl [&_h3]:font-semibold [&_img]:my-6 [&_img]:rounded-2xl [&_ul]:list-disc [&_ul]:pl-6">
            {!! $post->content !!}
        </div>

        {{-- Like / Dislike --}}
        <div class="mt-8 flex gap-3">
            <button wire:click="like" @if ($userDisliked) disabled @endif wire:loading.attr="disabled"
                    class="flex items-center gap-2 rounded-full px-5 py-2 text-sm font-semibold transition {{ $userLiked ? 'bg-emerald-600 text-white' : 'border border-slate-300 text-slate-600 hover:bg-slate-50' }}">
                👍 {{ __('page_blog_show.like') }} ({{ $post->likes()->count() }})
            </button>
            <button wire:click="dislike" @if ($userLiked) disabled @endif wire:loading.attr="disabled"
                    class="flex items-center gap-2 rounded-full px-5 py-2 text-sm font-semibold transition {{ $userDisliked ? 'bg-rose-600 text-white' : 'border border-slate-300 text-slate-600 hover:bg-slate-50' }}">
                👎 {{ __('page_blog_show.dislike') }} ({{ $post->dislikes()->count() }})
            </button>
        </div>

        {{-- Compartilhar --}}
        <div class="mt-10 border-t border-slate-100 pt-6">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">{{ __('page_blog_show.share') }}</h3>
            <div class="mt-3 flex gap-3">
                @foreach ([
                    ['Facebook', 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($postUrl)],
                    ['Twitter/X', 'https://twitter.com/intent/tweet?url=' . urlencode($postUrl) . '&text=' . urlencode($post->title)],
                    ['LinkedIn', 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($postUrl) . '&title=' . urlencode($post->title)],
                    ['WhatsApp', 'https://api.whatsapp.com/send?text=' . urlencode($post->title . ' - ' . $postUrl)],
                ] as [$rede, $url])
                    <a href="{{ $url }}" target="_blank" rel="noopener" class="rounded-full border border-slate-300 px-4 py-1.5 text-sm font-medium text-slate-600 transition hover:border-violet-400 hover:text-violet-600">{{ $rede }}</a>
                @endforeach
            </div>
        </div>

        {{-- Comentários --}}
        <section class="mt-12">
            <livewire:site.components.blog.comments :post="$post" />
        </section>
    </article>
</div>
