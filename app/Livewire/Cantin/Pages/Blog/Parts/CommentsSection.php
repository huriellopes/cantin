<?php

namespace App\Livewire\Cantin\Pages\Blog\Parts;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CommentsSection extends Component
{
    public Post $post;
    public string $newComment = '';
    public int $replyingTo = 0; // ID do comentário que está sendo respondido
    public array $replies = []; // Armazena o texto das respostas
    public array $showReplyForm = []; // Controla a visibilidade dos formulários de resposta

    public string $name = '';
    public string $email = '';

    public $userLiked;
    public $userDisliked;

    public $showCommentForm = false; // Adicione esta propriedade

    protected function rules()
    {
        // Se o usuário não estiver logado, nome e email são obrigatórios.
        return [
            'name' => Auth::check() ? 'nullable' : 'required|min:3',
            'email' => Auth::check() ? 'nullable' : 'required|email',
            'newComment' => 'required|min:3',
            'replies.*' => 'required|min:3',
        ];
    }

    public function mount(Post $post)
    {
        $this->post = $post;

        // Se o usuário estiver logado, preenche o nome e email automaticamente
        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }

        // Inicializa o array de visibilidade para cada comentário
        foreach ($this->post->comments as $comment) {
            $this->showReplyForm[$comment->id] = false;
        }

        $this->userLiked = $post->likes()->where('user_id', '=', auth()->id())->exists();
        $this->userDisliked = $post->dislikes()->where('user_id', '=', auth()->id())->exists();
    }

    public function postComment()
    {
        $this->validateOnly('newComment');

        if (Auth::check()) {
            Auth::user()->comments()->create([
                'ip_address' => request()->ip(),
                'post_id' => $this->post->id,
                'body' => $this->newComment,
            ]);
        } else {
            // Cria um comentário anônimo
            $comment = new Comment([
                'name' => $this->name,
                'email' => $this->email,
                'ip_address' => request()->ip(),
                'post_id' => $this->post->id,
                'body' => $this->newComment,
            ]);
            $comment->user_id = null; // Ou um ID de usuário anônimo
            $comment->save();
        }

        $this->name = '';
        $this->email = '';
        $this->newComment = '';
        $this->post->refresh();
    }

    public function postReply(Comment $parentComment)
    {
        try {
            $this->validateOnly('replies.' . $parentComment->id, [
                'replies.' . $parentComment->id => 'required|string|max:1000',
            ]);
        } catch (ValidationException $e) {
            throw $e;
        }

        if (auth()->check()) {
            if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')) {
                $reply = auth()->user()->comments()->create([
                    'ip_address' => request()->ip(),
                    'post_id' => $this->post->id,
                    'body' => $this->replies[$parentComment->id],
                ]);

                $parentComment->update([
                    'parent_id' => $reply->id
                ]);

                Log::info('User ' . auth()->user()->id . ' replied to comment ' . $parentComment->id . ' successfully.');
                session()->flash('success', 'Sua resposta foi enviada com sucesso!');
            } else {
                Log::error('User ' . auth()->user()->id . ' tried to reply to comment ' . $parentComment->id . ' without admin or super-user role.');
                session()->flash('error', 'Você não tem permissão para responder a este comentário.');
            }
        } else {
            Log::error('User tried to reply to comment ' . $parentComment->id . ' without being logged in.');
            session()->flash('error', 'É necessário estar logado como administrador para responder a este comentário.');
        }

        $this->replies[$parentComment->id] = '';
        $this->showReplyForm[$parentComment->id] = false;
        $this->post->refresh();
    }

    public function toggleReplyForm(int $commentId)
    {
        // Alterna o estado de visibilidade
        $this->showReplyForm[$commentId] = !$this->showReplyForm[$commentId];
    }

    public function likeComment(Comment $comment)
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

    public function dislikeComment(Comment $comment)
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


    public function toggleCommentForm()
    {
        $this->showCommentForm = !$this->showCommentForm;
    }

    public function render()
    {
        return view('livewire.cantin.pages.blog.parts.comments-section', [
            'comments' => $this->post->comments()->whereNull('parent_id')->with(['replies'])->paginate(10),
        ]);
    }
}
