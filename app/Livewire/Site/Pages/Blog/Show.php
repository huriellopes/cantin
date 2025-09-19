<?php

namespace App\Livewire\Site\Pages\Blog;

use App\Models\Post;
use Livewire\Component;

class Show extends Component
{
    public Post $post;

    public $userLiked;
    public $userDisliked;

    public $postUrl;

    public function mount(Post $post): void
    {
        $this->post = $post->query()
            ->published()
            ->where('slug', '=', $this->post->slug)
            ->sole();

        $this->userLiked = $post->likes()->where('user_id', '=', auth()->id())->exists();
        $this->userDisliked = $post->dislikes()->where('user_id', '=', auth()->id())->exists();

        $this->postUrl = url()->current();
    }

    public function like()
    {
        // Se o usuário já deu like, remove o like
        if ($this->userLiked) {
            $this->post->likes()->where('user_id', auth()->id())->delete();
            $this->userLiked = false;
        } else {
            // Se o usuário tinha dado dislike, remove o dislike primeiro
            if ($this->userDisliked) {
                $this->post->dislikes()->where('user_id', auth()->id())->delete();
                $this->userDisliked = false;
            }

            if (auth()->check()) {
                $data = [
                    'user_id' => auth()->id(),
                    'comment_id' => null,
                    'ip_address' => request()->ip(),
                    'post_id' => $this->post->id
                ];
            } else {
                $data = [
                    'user_id' => null,
                    'comment_id' => null,
                    'ip_address' => request()->ip(),
                    'post_id' => $this->post->id
                ];
            }

            // Adiciona o like
            $this->post->likes()->create($data);
            $this->userLiked = true;
        }
    }

    public function dislike()
    {
        // Se o usuário já deu dislike, remove o dislike
        if ($this->userDisliked) {
            $this->post->dislikes()->where('user_id', auth()->id())->delete();
            $this->userDisliked = false;
        } else {
            // Se o usuário tinha dado like, remove o like primeiro
            if ($this->userLiked) {
                $this->post->likes()->where('user_id', auth()->id())->delete();
                $this->userLiked = false;
            }

            if (auth()->check()) {
                $data = [
                    'user_id' => auth()->id(),
                    'comment_id' => null,
                    'ip_address' => request()->ip(),
                    'post_id' => $this->post->id
                ];
            } else {
                $data = [
                    'user_id' => null,
                    'comment_id' => null,
                    'ip_address' => request()->ip(),
                    'post_id' => $this->post->id
                ];
            }

            // Adiciona o dislike
            $this->post->dislikes()->create($data);
            $this->userDisliked = true;
        }
    }

    public function render()
    {
        $this->post->increment('views');
        return view('livewire.site.pages.blog.show');
    }
}
