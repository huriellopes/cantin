@props(['post'])
<div x-data="{ expanded: false }">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Comentários <span class="badge bg-primary rounded-pill">{{ $post->comments->count() }}</span></h3>
        <button @click="expanded = ! expanded" class="btn btn-sm btn-outline-primary" wire:loading.attr="disabled">
            <i class="bi bi-pencil-fill me-1"></i>Novo comentário
        </button>
    </div>

    <div wire:ignore.self x-show="expanded" x-collapse.duration.500ms>
        <div id="commentForm" class="collapse show mb-5">
            <h5 class="mb-3">Deixe seu comentário</h5>
            <form wire:submit.prevent="store">
                <div class="row g-3">
                    @guest
                        <div class="col-md-6">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" class="form-control" name="name" id="name" wire:model="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input type="email" class="form-control" name="email" id="email" wire:model="email" required>
                        </div>
                    @else
                        <div class="col-12">
                            <p class="text-muted">Comentando como **{{ Auth::user()->name }}**</p>
                        </div>
                    @endguest

                    <div class="col-12">
                        <label for="newComment" class="form-label">{{ __('Comment') }}</label>
                        <textarea class="form-control" id="newComment" rows="4" wire:model="newComment"></textarea>
                        @error('newComment') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="sendComment">
                            <i class="bi bi-send-fill me-1"></i> Enviar comentário
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="comment-list">
        @foreach ($comments as $comment)
            <div class="comment">
                <div class="d-flex mb-3">
                    <img src="{{ asset('/assets/images/avatar.png') }}" alt="{{ $comment->user?->name ?? $comment->name }}" class="rounded-circle me-3" width="50">
                    <div>
                        <h6 class="mb-2 fw-semibold">
                            {{ $comment->user?->name ?? $comment->name }}

                            @if ($comment->user)
                                @if ($comment->post->user_id === $comment->user->id)
                                    <span class="badge bg-primary ms-2">Autor</span>
                                @endif
                            @endif
                        </h6>
                        <small class="text-muted d-flex align-items-center">
                            <span>Postado em {{ $comment->created_at->format('d/m/Y') }}</span>
                        </small>
                    </div>
                </div>
                <p class="mb-2">{{ $comment->body }}</p>
                <div class="d-flex align-items-center">
                    @php
                        $userHasLiked = auth()->check() ? $comment->likes->contains('user_id', auth()->id()) : $comment->likes->contains('ip_address', request()->ip());
                        $userHasDisliked = auth()->check() ? $comment->dislikes->contains('user_id', auth()->id()) : $comment->dislikes->contains('ip_address', request()->ip());
                    @endphp

                    <button wire:click="likeComment({{ $comment->id }})"
                            wire:loading.attr="disabled"
                            class="btn btn-sm me-2 @if($userHasLiked) btn-primary @else btn-outline-secondary @endif"
                            @if($userHasDisliked) disabled @endif>
                        <i class="bi bi-hand-thumbs-up me-1"></i>
                        {{ $comment->likes->count() }}
                    </button>

                    <button wire:click="dislikeComment({{ $comment->id }})"
                            wire:loading.attr="disabled"
                            class="btn btn-sm me-2 @if($userHasDisliked) btn-danger @else btn-outline-secondary @endif"
                            @if($userHasLiked) disabled @endif>
                        <i class="bi bi-hand-thumbs-down me-1"></i>
                        {{ $comment->dislikes->count() }}
                    </button>

                    @if (auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')))
                        <button wire:click="toggleReplyForm({{ $comment->id }})" class="btn btn-sm btn-outline-primary" wire:loading.attr="disabled">
                            <i class="bi bi-reply-fill me-1"></i>Responder
                        </button>
                    @endif
                </div>

                @if ($showReplyForm[$comment->id] ?? false)
                    <div class="collapse show mt-3" id="replyForm{{ $comment->id }}">
                        <div class="card card-body bg-light">
                            <form wire:submit.prevent="postReply({{ $comment->id }})">
                                <div class="mb-3">
                                    <label for="reply-{{ $comment->id }}" class="form-label">Sua resposta</label>
                                    <textarea wire:model.defer="replies.{{ $comment->id }}" class="form-control" id="reply-{{ $comment->id }}" rows="3" placeholder="Digite sua resposta..."></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-sm">Enviar</button>
                                    <button type="button" wire:click="toggleReplyForm({{ $comment->id }})" class="btn btn-outline-secondary btn-sm ms-2">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                @if ($comment->replies->isNotEmpty())
                    @foreach ($comment->replies as $reply)
                        <div class="comment comment-reply mt-3">
                            <div class="d-flex mb-3">
                                <img src="{{ asset('/assets/images/avatar.png') }}" alt="{{ !empty($reply->user) ? $reply->user->name : $reply->name }}" class="rounded-circle me-3" width="50">

                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ $reply->user?->name ?? $reply->name }}
                                        @if ($reply?->user?->id === $post->user_id)
                                            <span class="badge bg-primary ms-2">Autor</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted">Postado em {{ $reply->created_at->format('d/m/Y') }}</small>
                                </div>
                            </div>
                            <p class="mb-2">{{ $reply->body }}</p>
                            <div class="d-flex align-items-center">
                                @php
                                    $userHasLikedReply = auth()->check() ? $reply->likes->contains('user_id', auth()->id()) : $reply->likes->contains('ip_address', request()->ip());
                                    $userHasDislikedReply = auth()->check() ? $reply->dislikes->contains('user_id', auth()->id()) : $reply->dislikes->contains('ip_address', request()->ip());
                                @endphp

                                <button wire:click="likeComment({{ $reply->id }})"
                                        class="btn btn-sm me-2 @if($userHasLikedReply) btn-primary @else btn-outline-secondary @endif"
                                        wire:loading.attr="disabled"
                                        @if($userHasDislikedReply) disabled @endif>
                                    <i class="bi bi-hand-thumbs-up me-1"></i>
                                    {{ $reply->likes->count() }}
                                </button>

                                <button wire:click="dislikeComment({{ $reply->id }})"
                                        class="btn btn-sm me-2 @if($userHasDislikedReply) btn-danger @else btn-outline-secondary @endif"
                                        wire:loading.attr="disabled"
                                        @if($userHasLikedReply) disabled @endif>
                                    <i class="bi bi-hand-thumbs-down me-1"></i>
                                    {{ $reply->dislikes->count() }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
</div>
