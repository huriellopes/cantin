<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages\Blog;

use App\Enum\Status;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public $selectedCategory = null;

    public object $categories;

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

    public function mount(): void
    {
        $this->categories = Cache::remember('categories_cantin', 60 * 60 * 24, function () {
            return Category::query()
                ->select(['id', 'name', 'slug'])
                ->where('status', '=', Status::ACTIVE)
                ->withCount('posts')
                ->get();
        });
    }

    public function render()
    {
        return view('livewire.site.pages.blog.posts', [
            'posts' => Post::query()
                ->with(['category:id,name', 'user:id,name'])
                ->published()
                ->when($this->selectedCategory, function ($query) {
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
