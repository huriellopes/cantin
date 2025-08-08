<div class="card" style="width: 18rem;">
    @if (!empty($post->main_image))
        <img src="{{ asset("/storage/{$post->main_image}") }}" class="card-img-top" alt="{{ $post->slug }}" title="{{ $post->slug }}" />
    @endif
    <div class="card-body">
        <h5 class="card-title">
            {{ $post->title }}
        </h5>

        <p class="card-text">
            {!! str($post->content)->limit(50) !!}
        </p>

        <a href="{{ route('site.blog.show', $post->slug) }}" class="btn btn-primary">
            {{ __('Read more...') }}
        </a>
    </div>
</div>
