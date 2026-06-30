<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Posts;

use App\Enum\StatusPost;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Admin\Support\WithDataTable;
use App\Models\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Posts')]
class Index extends Component
{
    use HasAdminActions, WithDataTable, WithPagination;

    public function view(int $id): void
    {
        $post = Post::query()->with(['user:id,name', 'category:id,name'])->findOrFail($id);
        $this->viewData = [
            ['label' => __('msg_posts.label_title'), 'value' => $post->title],
            ['label' => __('msg_posts.label_category'), 'value' => $post->category?->name],
            ['label' => __('msg_posts.label_author'), 'value' => $post->user?->name],
            ['label' => __('msg_posts.label_publication'), 'value' => $post->published_at?->format('d/m/Y')],
            ['label' => __('msg_posts.label_status'), 'value' => $post->status?->label()],
            ['label' => __('msg_posts.label_views'), 'value' => $post->views],
            ['label' => __('msg_posts.label_content'), 'value' => strip_tags((string) $post->content)],
        ];
        $this->viewTitle = $post->title;
        $this->showView = true;
    }

    public function publish(int $id): void
    {
        $post = Post::query()->findOrFail($id);

        if ($post->published_at?->startOfDay()->gt(today())) {
            $this->notify(__('msg_posts.publish_date_future'), 'warning');

            return;
        }

        $post->update(['status' => StatusPost::PUBLISHED]);
        $this->notify(__('msg_posts.post_published'));
    }

    public function unpublish(int $id): void
    {
        Post::query()->findOrFail($id)->update(['status' => StatusPost::PENDING]);
        $this->notify(__('msg_posts.post_unpublished'));
    }

    public function delete(int $id): void
    {
        Post::query()->findOrFail($id)->delete();
        $this->notify(__('msg_posts.post_deleted'));
    }

    public function render(): Factory|View
    {
        $queryBase = Post::query()
            ->withCount('likes')
            ->with('user:id,name');

        $posts = $this->applyTable($queryBase, ['title']);

        return view('livewire.admin.posts.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * @return array<int, string>
     */
    protected function sortableColumns(): array
    {
        return ['id', 'title', 'published_at', 'views', 'status'];
    }
}
