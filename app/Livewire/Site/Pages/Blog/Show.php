<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages\Blog;

use App\Models\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Spatie\SchemaOrg\Schema;

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

        seo()
            ->title($this->post->title)
            ->description(str($this->post->content)->stripTags()->squish()->limit(155)->toString())
            ->image($this->post->main_image ? asset($this->post->main_image) : asset('assets/images/CANTIn.png'))
            ->type('article');
    }

    public function like(): void
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
                    'post_id' => $this->post->id,
                ];
            } else {
                $data = [
                    'user_id' => null,
                    'comment_id' => null,
                    'ip_address' => request()->ip(),
                    'post_id' => $this->post->id,
                ];
            }

            // Adiciona o like
            $this->post->likes()->create($data);
            $this->userLiked = true;
        }
    }

    public function dislike(): void
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
                    'post_id' => $this->post->id,
                ];
            } else {
                $data = [
                    'user_id' => null,
                    'comment_id' => null,
                    'ip_address' => request()->ip(),
                    'post_id' => $this->post->id,
                ];
            }

            // Adiciona o dislike
            $this->post->dislikes()->create($data);
            $this->userDisliked = true;
        }
    }

    public function render(): Factory|View
    {
        $this->post->increment('views');

        $article = Schema::article()
            ->headline($this->post->title)
            ->description(str($this->post->content)->stripTags()->squish()->limit(155)->toString())
            ->datePublished(Carbon::parse($this->post->published_at))
            ->dateModified($this->post->updated_at)
            ->author(Schema::person()->name($this->post->user->name))
            ->publisher(Schema::organization()->name(config('app.name')))
            ->mainEntityOfPage($this->postUrl);

        if ($this->post->main_image) {
            $article->image(asset($this->post->main_image));
        }

        return view('livewire.site.pages.blog.show', [
            'articleJsonLd' => $article->toScript(),
        ]);
    }
}
