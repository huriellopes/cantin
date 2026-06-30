@props(['post'])
@php $fieldCls = 'block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-violet-500 focus:ring-violet-500'; @endphp

<div x-data="{ expanded: false }">
    <div class="mb-5 flex items-center justify-between">
        <h3 class="text-xl font-bold text-slate-800">
            {{ __('comp_comments.title') }} <span class="ml-1 rounded-full bg-violet-100 px-2 py-0.5 text-sm text-violet-700">{{ $post->comments->count() }}</span>
        </h3>
        <button @click="expanded = ! expanded" class="rounded-full bg-gradient-to-r from-violet-600 to-pink-500 px-4 py-1.5 text-sm font-semibold text-white transition hover:brightness-110">
            {{ __('comp_comments.new_comment') }}
        </button>
    </div>

    <div x-show="expanded" x-transition x-cloak class="mb-8 rounded-2xl border border-slate-200 bg-slate-50 p-5">
        <h5 class="mb-3 font-semibold text-slate-700">{{ __('comp_comments.leave_your_comment') }}</h5>
        <form wire:submit.prevent="store" class="space-y-3">
            @guest
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <input type="text" wire:model="name" placeholder="{{ __('Name') }}" class="{{ $fieldCls }}">
                    <input type="email" wire:model="email" placeholder="{{ __('Email') }}" class="{{ $fieldCls }}">
                </div>
            @else
                <p class="text-sm text-slate-500">{{ __('comp_comments.commenting_as') }} <strong>{{ Auth::user()->name }}</strong></p>
            @endguest
            <div>
                <textarea wire:model="newComment" rows="4" placeholder="{{ __('Comment') }}" class="{{ $fieldCls }}"></textarea>
                @error('newComment') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
            </div>
            <button type="submit" wire:loading.attr="disabled" wire:target="store" class="rounded-full bg-violet-600 px-5 py-2 text-sm font-semibold text-white hover:bg-violet-700">{{ __('comp_comments.submit_comment') }}</button>
        </form>
    </div>

    <div class="space-y-6">
        @foreach ($comments as $comment)
            <div wire:key="c-{{ $comment->id }}">
                <div class="flex gap-3">
                    <img src="{{ asset('/assets/images/avatar.png') }}" alt="" class="h-11 w-11 rounded-full">
                    <div class="flex-1">
                        <h6 class="font-semibold text-slate-800">
                            {{ $comment->user?->name ?? $comment->name }}
                            @if ($comment->user && $comment->post->user_id === $comment->user->id)
                                <span class="ml-2 rounded-full bg-violet-100 px-2 py-0.5 text-xs text-violet-700">{{ __('comp_comments.author') }}</span>
                            @endif
                        </h6>
                        <small class="text-xs text-slate-400">{{ __('comp_comments.posted_on') }} {{ $comment->created_at?->format('d/m/Y') }}</small>
                        <p class="mt-2 text-slate-600">{{ $comment->body }}</p>

                        @php
                            $userHasLiked = auth()->check() ? $comment->likes->contains('user_id', auth()->id()) : $comment->likes->contains('ip_address', request()->ip());
                            $userHasDisliked = auth()->check() ? $comment->dislikes->contains('user_id', auth()->id()) : $comment->dislikes->contains('ip_address', request()->ip());
                        @endphp
                        <div class="mt-2 flex items-center gap-2 text-sm">
                            <button wire:click="likeComment({{ $comment->id }})" wire:loading.attr="disabled" @if($userHasDisliked) disabled @endif
                                    class="rounded-full px-3 py-1 {{ $userHasLiked ? 'bg-violet-600 text-white' : 'border border-slate-300 text-slate-600 hover:bg-slate-50' }}">👍 {{ $comment->likes->count() }}</button>
                            <button wire:click="dislikeComment({{ $comment->id }})" wire:loading.attr="disabled" @if($userHasLiked) disabled @endif
                                    class="rounded-full px-3 py-1 {{ $userHasDisliked ? 'bg-rose-600 text-white' : 'border border-slate-300 text-slate-600 hover:bg-slate-50' }}">👎 {{ $comment->dislikes->count() }}</button>
                            @if (auth()->check() && auth()->user()->hasRole('admin', 'super-admin'))
                                <button wire:click="toggleReplyForm({{ $comment->id }})" class="rounded-full border border-violet-200 px-3 py-1 text-violet-700 hover:bg-violet-50">{{ __('comp_comments.reply') }}</button>
                            @endif
                        </div>

                        @if ($showReplyForm[$comment->id] ?? false)
                            <form wire:submit.prevent="postReply({{ $comment->id }})" class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <textarea wire:model.defer="replies.{{ $comment->id }}" rows="3" placeholder="{{ __('comp_comments.reply_placeholder') }}" class="{{ $fieldCls }}"></textarea>
                                <div class="mt-2 flex justify-end gap-2">
                                    <button type="button" wire:click="toggleReplyForm({{ $comment->id }})" class="rounded-full px-4 py-1.5 text-sm text-slate-500 hover:bg-slate-100">{{ __('common.cancel') }}</button>
                                    <button type="submit" class="rounded-full bg-violet-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-violet-700">{{ __('comp_comments.send') }}</button>
                                </div>
                            </form>
                        @endif

                        @if ($comment->replies->isNotEmpty())
                            <div class="mt-4 space-y-4 border-l-2 border-slate-100 pl-4">
                                @foreach ($comment->replies as $reply)
                                    <div class="flex gap-3" wire:key="r-{{ $reply->id }}">
                                        <img src="{{ asset('/assets/images/avatar.png') }}" alt="" class="h-9 w-9 rounded-full">
                                        <div>
                                            <h6 class="text-sm font-semibold text-slate-800">
                                                {{ $reply->user?->name ?? $reply->name }}
                                                @if ($reply?->user?->id === $post->user_id)
                                                    <span class="ml-2 rounded-full bg-violet-100 px-2 py-0.5 text-xs text-violet-700">{{ __('comp_comments.author') }}</span>
                                                @endif
                                            </h6>
                                            <small class="text-xs text-slate-400">{{ __('comp_comments.posted_on') }} {{ $reply->created_at?->format('d/m/Y') }}</small>
                                            <p class="mt-1 text-sm text-slate-600">{{ $reply->body }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
