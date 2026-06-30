<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Posts;

use App\Enum\StatusPost;
use App\Models\Category;
use App\Models\Post;
use App\Support\HtmlSanitizer;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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

    public ?int $editingId = null;

    public string $titleField = '';

    public string $slug = '';

    public ?int $category_id = null;

    public string $published_at = '';

    public string $content = '';

    public $image;

    public ?string $currentImage = null;

    public function mount(?Post $post = null): void
    {
        if ($post instanceof Post && $post->exists) {
            $this->editingId = $post->id;
            $this->titleField = $post->title;
            $this->slug = $post->slug;
            $this->category_id = $post->category_id;
            $this->published_at = Date::parse($post->published_at)->format('Y-m-d');
            $this->content = $post->content;
            $this->currentImage = $post->main_image;

            return;
        }

        $this->published_at = now()->format('Y-m-d');
    }

    public function save(): void
    {
        $this->validate();

        $publishedAt = Date::parse($this->published_at);

        $payload = [
            'title' => $this->titleField,
            'slug' => Str::slug($this->slug ?: $this->titleField),
            'category_id' => $this->category_id,
            'published_at' => $publishedAt,
            'content' => HtmlSanitizer::clean($this->content),
            'status' => $publishedAt->startOfDay()->lte(today()) ? StatusPost::PUBLISHED : StatusPost::PENDING,
        ];

        if ($this->image) {
            $payload['main_image'] = $this->image->store('posts', 'public');
        }

        if (!$this->editingId) {
            $payload['user_id'] = auth()->id();
        }

        $editing = (bool) $this->editingId;

        if ($editing) {
            Post::query()->whereKey($this->editingId)->update($payload);
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

    protected function rules(): array
    {
        return [
            'titleField' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', Rule::unique('posts', 'slug')->ignore($this->editingId)],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'published_at' => ['required', 'date'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
