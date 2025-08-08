<?php

namespace App\Livewire\Cantin\Pages\Blog;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Posts extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public $selectedCategory = null;

    public $categories;

    // No componente
    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => null, 'as' => 'category'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectCategory($categorySlug): void
    {
        $this->selectedCategory = $categorySlug;
        $this->resetPage();
    }

    public function clearCategory(): void
    {
        $this->selectedCategory = null;
        $this->queryString = [];
        $this->resetPage();
    }

    public function mount(Post $post): void
    {
        $this->categories = Category::query()
            ->withCount('posts')
            ->get();
    }

    public function render()
    {
        return view('livewire.cantin.pages.blog.posts', [
            'posts' => Post::query()
                ->with(['category:id,name', 'user:id,name'])
                ->published()
                ->when($this->selectedCategory, function($query) {
                    return $query->whereHas('category', function ($query) {
                        $query->where('slug', $this->selectedCategory);
                    });
                })
                ->search($this->search)
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            'categories' => $this->categories,
        ]);
    }
}
