@assets
<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
    }

    body {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        line-height: 1.6;
        color: #212529;
    }

    .post-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 4rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
        .post-header {
            padding: 2.5rem 0;
        }
    }

    .post-content {
        font-size: 1.1rem;
    }

    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 2rem auto;
        display: block;
    }

    .post-content h2 {
        margin-top: 2.5rem;
        margin-bottom: 1.25rem;
        font-weight: 600;
    }

    .post-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    blockquote {
        border-left: 4px solid #0d6efd;
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        margin: 2rem 0;
        font-style: italic;
    }

    .author-card {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }

    .author-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.1);
    }

    @media (max-width: 576px) {
        .author-img {
            width: 80px;
            height: 80px;
        }
    }

    .comment {
        position: relative;
        padding-left: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .comment::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background-color: #0d6efd;
        border-radius: 3px;
    }

    .comment-reply {
        margin-left: 2.5rem;
    }

    .comment-reply::before {
        background-color: #6c757d;
    }

    @media (max-width: 576px) {
        .comment-reply {
            margin-left: 1.5rem;
        }
    }

    .tag-badge {
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        transition: all 0.2s;
    }

    .tag-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
    }

    .social-share .btn {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.5rem;
        transition: all 0.2s;
    }

    .social-share .btn:hover {
        transform: translateY(-3px);
    }

    .sidebar-card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        margin-bottom: 1.5rem;
    }

    .sidebar-card .card-header {
        background-color: #0d6efd;
        color: white;
        font-weight: 600;
        border: none;
    }

    .sidebar-list-item {
        border-left: none;
        border-right: none;
        padding: 0.75rem 1.25rem;
        transition: all 0.2s;
    }

    .sidebar-list-item:hover {
        background-color: #f8f9fa;
    }

    .sidebar-list-item:first-child {
        border-top: none;
    }

    .sidebar-list-item:last-child {
        border-bottom: none;
    }
</style>
@endassets
<div>
    <!-- Cabeçalho do Post -->
    <header class="post-header">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="text-center">
                        <span class="badge bg-primary mb-3 fs-6 fw-normal">{{ $post->category->name }}</span>
                        <h1 class="display-5 fw-bold mb-4">{{ $post->title }}</h1>

                        <div class="d-flex align-items-center justify-content-center">
                            <img src="{{ asset('/assets/images/avatar.png') }}" alt="{{ $post->user->name }}" title="{{ $post->user->name }}" class="rounded-circle me-3" width="56">
                            <div class="text-start">
                                <p class="mb-0 fw-medium">{{ $post->user->name }}</p>
                                <p class="mb-0 text-muted small">
                                    <span>Publicado em {{ $post->published_at->format('d/m/Y') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ readingTime($post->content) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row g-4">
            <!-- Conteúdo Principal -->
            <main class="col-lg-12">
                {!! str($post->content)->sanitizeHtml() !!}

                <div class="mb-3 mt-3">
                    <button wire:click="like"
                            class="btn btn-sm {{ $userLiked ? 'btn-success' : 'btn-outline-success' }}"
                            @if ($userDisliked) disabled @endif wire:loading.attr="disabled">
                        <i class="fa fa-thumbs-up"></i>
                        Curtir ({{ $post->likes()->count() }})
                        <div class="spinner-border" role="status" wire:loading wire:target="like">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>

                    <button wire:click="dislike"
                            class="btn btn-sm {{ $userDisliked ? 'btn-danger' : 'btn-outline-danger' }}"
                            @if ($userLiked) disabled @endif wire:loading.attr="disabled">
                        <i class="fa fa-thumbs-down"></i>
                        Descurtir ({{ $post->dislikes()->count() }})
                        <div class="spinner-border" role="status" wire:loading wire:target="dislike">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </div>

                <!-- Compartilhamento -->
                <div class="social-share mb-5">
                    <h5 class="mb-3">Compartilhe este conteúdo:</h5>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($postUrl) }}"
                       target="_blank"
                       class="btn btn-primary rounded-circle"
                       title="Compartilhar no Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($postUrl) }}&text={{ urlencode($post->title) }}"
                       target="_blank"
                       class="btn btn-info rounded-circle text-white"
                       title="Compartilhar no Twitter/X">
                        <i class="bi bi-twitter-x"></i>
                    </a>

                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($postUrl) }}&title={{ urlencode($post->title) }}"
                       target="_blank"
                       class="btn btn-dark rounded-circle"
                       title="Compartilhar no LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>

                    <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title) }}%20-%20{{ urlencode($postUrl) }}"
                       target="_blank"
                       class="btn btn-success rounded-circle"
                       title="Compartilhar no WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>

                <!-- Comentários -->
                <section class="comments mb-5">
                    <livewire:site.components.blog.comments :post="$post" />
                </section>
            </main>
        </div>
    </div>
</div>
