@assets
<style>
    .category-list {
        list-style: none;
        padding-left: 0;
    }

    .category-list li {
        margin-bottom: 8px;
    }

    .category-list a {
        text-decoration: none;
        color: #495057;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .category-list a:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .category-list .badge {
        background-color: #e9ecef;
        color: #495057;
    }

    .post-card {
        height: 100%;
        transition: transform 0.3s;
    }

    .post-card:hover {
        transform: translateY(-5px);
    }
    .post-img {
        height: 200px;
        object-fit: cover;
    }
    @media (max-width: 767.98px) {
        .sidebar {
            margin-bottom: 30px;
        }
    }
</style>
@endassets
<div>
    <div class="container py-5 mt-5">
        <div class="row">
            <!-- Sidebar com busca -->
            <aside class="col-lg-3 col-md-4 sidebar">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-bookmarks-fill me-2"></i>{{ __('Search') }}</h5>
                    </div>
                    <div class="card-body">
                        <form class="d-flex">
                            <input class="form-control me-2" type="search" wire:model.live.debounce.150ms="search" placeholder="Digite aqui..." aria-label="Search">
                        </form>
                    </div>
                </div>

                <!-- Sidebar com categorias -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-bookmarks-fill me-2"></i>
                            <span>Categorias</span>
                            @if($selectedCategory)
                                <button wire:click="clearCategory" class="btn btn-sm btn-outline-light ms-auto">
                                    Limpar
                                </button>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="category-list">
                            @foreach ($categories as $category)
                                <li>
                                    <a href="?category={{ $category->slug }}" wire:navigate wire:click="selectCategory({{ $category->slug }})"
                                       class="text-decoration-none {{ $selectedCategory == $category->slug ? 'fw-bold text-primary' : '' }}">
                                        {{ $category->name }} <span class="badge rounded-pill">( {{ $category->posts->count() }} )</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </aside>

            <!-- Área principal com posts -->
            <main class="col-lg-9 col-md-8">
                @if($selectedCategory)
                    <div class="alert alert-info mb-3" style="width: 100%;">
                        Mostrando posts da categoria:
                        <strong>{{ $categories->firstWhere('slug', $selectedCategory)->name }}</strong>
                    </div>
                @endif
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <!-- Posts -->
                    @if ($posts->isEmpty())
                            <div class="alert alert-warning" style="width: 100%;">
                                Nenhum post encontrado com os critérios atuais.
                            </div>
                    @else
                            @foreach($posts as $post)
                                <x-partials.blog.card :post="$post" />
                            @endforeach
                    @endif
                </div>

                <!-- Paginação -->
                {{ $posts->links() }}
            </main>
        </div>
    </div>
</div>
