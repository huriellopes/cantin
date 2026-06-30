<?php

namespace App\Livewire\Admin\Posts;

use App\Enum\StatusPost;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Posts')]
class Index extends Component
{
    use HasAdminActions, WithFileUploads, WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $titleField = '';

    public string $slug = '';

    public ?int $category_id = null;

    public string $published_at = '';

    public string $content = '';

    public $image;

    public ?string $currentImage = null;

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

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset(['editingId', 'titleField', 'slug', 'category_id', 'content', 'image', 'currentImage']);
        $this->published_at = now()->format('Y-m-d');
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $post = Post::query()->findOrFail($id);
        $this->editingId = $post->id;
        $this->titleField = $post->title;
        $this->slug = $post->slug;
        $this->category_id = $post->category_id;
        $this->published_at = $post->published_at?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->content = $post->content;
        $this->currentImage = $post->main_image;
        $this->image = null;
        $this->resetValidation();
        $this->showModal = true;
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
            'content' => $this->content,
            'status' => $publishedAt->startOfDay()->lte(today()) ? StatusPost::PUBLISHED : StatusPost::PENDING,
        ];

        if ($this->image) {
            $payload['main_image'] = $this->image->store('posts', 'public');
        }

        if (! $this->editingId) {
            $payload['user_id'] = auth()->id();
        }

        $editing = (bool) $this->editingId;

        if ($editing) {
            Post::query()->whereKey($this->editingId)->update($payload);
        } else {
            Post::query()->create($payload);
        }

        $this->showModal = false;
        $this->notify($editing ? 'Post atualizado.' : 'Post criado.');
    }

    public function view(int $id): void
    {
        $post = Post::query()->with(['user:id,name', 'category:id,name'])->findOrFail($id);
        $this->viewData = [
            ['label' => 'Título', 'value' => $post->title],
            ['label' => 'Categoria', 'value' => $post->category?->name],
            ['label' => 'Autor', 'value' => $post->user?->name],
            ['label' => 'Publicação', 'value' => $post->published_at?->format('d/m/Y')],
            ['label' => 'Status', 'value' => $post->status?->label()],
            ['label' => 'Views', 'value' => $post->views],
            ['label' => 'Conteúdo', 'value' => strip_tags((string) $post->content)],
        ];
        $this->viewTitle = $post->title;
        $this->showView = true;
    }

    public function publish(int $id): void
    {
        $post = Post::query()->findOrFail($id);

        if ($post->published_at?->startOfDay()->gt(today())) {
            $this->notify('A data de publicação ainda é futura.', 'warning');

            return;
        }

        $post->update(['status' => StatusPost::PUBLISHED]);
        $this->notify('Post publicado.');
    }

    public function unpublish(int $id): void
    {
        Post::query()->findOrFail($id)->update(['status' => StatusPost::PENDING]);
        $this->notify('Post despublicado.');
    }

    public function delete(int $id): void
    {
        Post::query()->findOrFail($id)->delete();
        $this->notify('Post excluído.');
    }

    public function render(): Factory|View
    {
        $posts = Post::query()
            ->withCount('likes')
            ->with('user:id,name')
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.admin.posts.index', [
            'posts' => $posts,
            'categories' => Category::query()->orderBy('name')->pluck('name', 'id'),
        ]);
    }
}
