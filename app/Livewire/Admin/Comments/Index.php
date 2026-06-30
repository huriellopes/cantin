<?php

namespace App\Livewire\Admin\Comments;

use App\Enum\Status;
use App\Livewire\Admin\Support\HasAdminActions;
use App\Models\Comment;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Comentários')]
class Index extends Component
{
    use HasAdminActions, WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $replyingTo = null;

    public string $originalBody = '';

    public string $body = '';

    protected function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:1', 'max:500'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function reply(int $id): void
    {
        $comment = Comment::query()->findOrFail($id);
        $this->replyingTo = $comment->id;
        $this->originalBody = $comment->body;
        $this->body = '';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $parent = Comment::query()->findOrFail($this->replyingTo);

        Comment::query()->create([
            'body' => $this->body,
            'ip_address' => request()->ip(),
            'user_id' => auth()->id(),
            'parent_id' => $parent->id,
            'post_id' => $parent->post_id,
            'status' => Status::ACTIVE,
        ]);

        $this->showModal = false;
        $this->notify('Resposta publicada.');
    }

    public function view(int $id): void
    {
        $comment = Comment::query()->with(['user:id,name', 'post:id,title'])->findOrFail($id);
        $this->viewData = [
            ['label' => 'Autor', 'value' => $comment->user?->name ?? $comment->name],
            ['label' => 'Post', 'value' => $comment->post?->title],
            ['label' => 'Comentário', 'value' => $comment->body],
            ['label' => 'Status', 'value' => $comment->status?->label()],
            ['label' => 'Data', 'value' => $comment->created_at?->format('d/m/Y H:i')],
        ];
        $this->viewTitle = 'Comentário';
        $this->showView = true;
    }

    public function toggleStatus(int $id): void
    {
        $comment = Comment::query()->findOrFail($id);
        $comment->update([
            'status' => $comment->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE,
        ]);
        $this->notify('Status atualizado.');
    }

    public function render(): Factory|View
    {
        $comments = Comment::query()
            ->whereNull('parent_id')
            ->with(['user:id,name', 'post:id,title,slug'])
            ->when($this->search, fn ($q) => $q->where('body', 'like', "%{$this->search}%"))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.admin.comments.index', [
            'comments' => $comments,
        ]);
    }
}
