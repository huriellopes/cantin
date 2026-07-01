<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Posts;

use App\Enum\StatusPost;
use App\Livewire\Forms\PostForm;
use App\Models\Category;
use App\Models\Post;
use App\Support\HtmlSanitizer;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Página dedicada de criação/edição de post, com editor rico (Quill) para o
 * conteúdo. Substitui o antigo modal da listagem.
 */
#[Layout('components.layouts.admin')]
#[Title('Post')]
class Manage extends Component
{
    use WithFileUploads;

    public PostForm $form;

    public ?string $currentImage = null;

    public function mount(?Post $post = null): void
    {
        if ($post instanceof Post && $post->exists) {
            $this->form->editingId = $post->id;
            $this->form->titleField = $post->title;
            $this->form->slug = $post->slug;
            $this->form->category_id = $post->category_id;
            $this->form->published_at = Date::parse($post->published_at)->format('Y-m-d');
            $this->form->content = $post->content;
            $this->currentImage = $post->main_image;

            return;
        }

        $this->form->published_at = now()->format('Y-m-d');
    }

    public function save(): void
    {
        $this->form->validate();

        $publishedAt = Date::parse($this->form->published_at);

        $payload = [
            'title' => $this->form->titleField,
            'slug' => Str::slug($this->form->slug ?: $this->form->titleField),
            'category_id' => $this->form->category_id,
            'published_at' => $publishedAt,
            'content' => HtmlSanitizer::clean($this->form->content),
            'status' => $publishedAt->startOfDay()->lte(today()) ? StatusPost::PUBLISHED : StatusPost::PENDING,
        ];

        if ($this->form->image) {
            $payload['main_image'] = $this->form->image->store('posts', 'public');
        }

        if (!$this->form->editingId) {
            $payload['user_id'] = auth()->id();
        }

        $editing = (bool) $this->form->editingId;

        if ($editing) {
            Post::query()->whereKey($this->form->editingId)->update($payload);
        } else {
            Post::query()->create($payload);
        }

        session()->flash('toast', [
            'type' => 'success',
            'message' => $editing ? __('msg_posts.post_updated') : __('msg_posts.post_created'),
        ]);

        $this->redirectRoute('admin.posts.index');
    }

    public function render(): Factory|View
    {
        return view('livewire.admin.posts.manage', [
            'categories' => Category::query()->orderBy('name')->pluck('name', 'id'),
        ]);
    }
}
