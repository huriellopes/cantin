<?php

declare(strict_types=1);

namespace App\Livewire\Site\Components\Blog;

use App\Enum\StatusPost;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Comments extends Component
{
    public Post $post;

    public string $name = '';

    public string $email = '';

    public string $newComment = '';

    public int $replyingTo = 0;

    public array $replies = [];

    public array $showReplyForm = [];

    public $userLiked;

    public $userDisliked;

    public function mount(): void
    {
        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }

        foreach ($this->post->comments as $comment) {
            $this->showReplyForm[$comment->id] = false;
        }

        $this->userLiked = $this->post->likes()->where('user_id', '=', auth()->id())->exists();
        $this->userDisliked = $this->post->dislikes()->where('user_id', '=', auth()->id())->exists();
    }

    public function store(): void
    {
        $post = Post::query()
            ->where('slug', '=', $this->post->slug)
            ->where('status', '=', StatusPost::PUBLISHED)
            ->first();

        if (Auth::check()) {
            Auth::user()->comments()->create([
                'ip_address' => request()->ip(),
                'post_id' => $post->id,
                'body' => $this->newComment,
            ]);
        } else {
            // Cria um comentário anônimo
            $comment = new Comment([
                'name' => $this->name,
                'email' => $this->email,
                'ip_address' => request()->ip(),
                'post_id' => $post->id,
                'body' => $this->newComment,
            ]);
            $comment->user_id = null; // Ou um ID de usuário anônimo
            $comment->save();
        }

        $this->reset(['name', 'email', 'newComment']);
        $this->resetValidation();
        $post->refresh();
    }

    public function postReply(Comment $parentComment): void
    {
        $this->validateOnly('replies.' . $parentComment->id, [
            'replies.' . $parentComment->id => 'required|string|max:1000',
        ]);

        if (auth()->check()) {
            if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')) {
                $reply = auth()->user()->comments()->create([
                    'ip_address' => request()->ip(),
                    'post_id' => $this->post->id,
                    'body' => $this->replies[$parentComment->id],
                ]);

                $parentComment->update([
                    'parent_id' => $reply->id,
                ]);

                Log::info('User ' . auth()->user()->id . ' replied to comment ' . $parentComment->id . ' successfully.');
                session()->flash('success', __('msg_comments_site.reply_sent_success'));
            } else {
                Log::error('User ' . auth()->user()->id . ' tried to reply to comment ' . $parentComment->id . ' without admin or super-admin role.');
                session()->flash('error', __('msg_comments_site.no_permission_to_reply'));
            }
        } else {
            Log::error('User tried to reply to comment ' . $parentComment->id . ' without being logged in.');
            session()->flash('error', __('msg_comments_site.must_be_logged_as_admin'));
        }

        $this->replies[$parentComment->id] = '';
        $this->showReplyForm[$parentComment->id] = false;
        $this->post->refresh();
    }

    public function toggleReplyForm(int $commentId): void
    {
        $this->showReplyForm[$commentId] = !($this->showReplyForm[$commentId] ?? false);
    }

    public function likeComment(Comment $comment): void
    {
        $user = Auth::user();
        $ipAddress = request()->ip();

        if ($user) {
            $existingLike = $comment->likes()->where('user_id', $user->id)->first();

            if ($existingLike) {
                $existingLike->delete();
            } else {
                $comment->dislikes()->where('user_id', $user->id)->delete();
                $comment->likes()->create([
                    'user_id' => $user->id,
                    'ip_address' => $ipAddress,
                ]);
            }
        } else {
            $existingLike = $comment->likes()->where('ip_address', $ipAddress)->first();

            if ($existingLike) {
                $existingLike->delete();
            } else {
                $comment->dislikes()->where('ip_address', $ipAddress)->delete();
                $comment->likes()->create(['ip_address' => $ipAddress]);
            }
        }

        $this->post->refresh();
    }

    public function dislikeComment(Comment $comment): void
    {
        $user = Auth::user();
        $ipAddress = request()->ip();

        if ($user) {
            $existingDislike = $comment->dislikes()->where('user_id', $user->id)->first();

            if ($existingDislike) {
                $existingDislike->delete();
            } else {
                $comment->likes()->where('user_id', $user->id)->delete();
                $comment->dislikes()->create([
                    'user_id' => $user->id,
                    'ip_address' => $ipAddress,
                ]);
            }
        } else {
            $existingDislike = $comment->dislikes()->where('ip_address', $ipAddress)->first();

            if ($existingDislike) {
                $existingDislike->delete();
            } else {
                $comment->likes()->where('ip_address', $ipAddress)->delete();
                $comment->dislikes()->create(['ip_address' => $ipAddress]);
            }
        }

        $this->post->refresh();
    }

    public function render(): Factory|View
    {
        $post = Post::query()->where('id', $this->post->id)->firstOrFail();
        $comments = $post->comments()->whereNull('parent_id')->with(['replies'])->paginate(10);

        return view('livewire.site.components.blog.comments', [
            'comments' => $comments,
        ]);
    }

    protected function rules(): array
    {
        return [
            'name' => Auth::check() ? 'nullable' : 'required|min:3',
            'email' => Auth::check() ? 'nullable' : 'required|email',
            'newComment' => 'required|min:3',
            'replies.*' => 'required|min:3',
        ];
    }

    protected function messages(): array
    {
        return [
            'newComment.required' => __('msg_comments_site.comment_required'),
        ];
    }
}
