<div class="col">
    <div class="card post-card shadow-sm h-100">
        @if (!empty($post->main_image)) <img src="{{ asset("/storage/{$post->main_image}") }}" class="card-img-top post-img" alt="Post image"> @endif
        <div class="card-body">
            <span class="badge bg-primary mb-2">{{ $post->category->name }}</span>
            <h5 class="card-title">{{ $post->title }}</h5>
            <p class="card-text text-muted">{!! str($post->content)->limit(100) !!}</p>
        </div>
        <div class="card-footer bg-transparent">
            <small class="text-muted">Publicado em {{ $post->published_at->format('d/m/Y') }}</small>
            <a
                href="{{ route('site.blog.show', $post->slug) }}"
                class="btn btn-sm btn-outline-primary float-end"
                wire:navigate
            >
                {{ __('Read more...') }}
            </a>
        </div>
    </div>
</div>
