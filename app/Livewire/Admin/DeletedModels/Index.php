<?php

declare(strict_types=1);

namespace App\Livewire\Admin\DeletedModels;

use App\Livewire\Admin\Support\HasAdminActions;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\DeletedModels\Models\DeletedModel;
use Throwable;

#[Layout('components.layouts.admin')]
#[Title('Modelos Excluídos')]
class Index extends Component
{
    use HasAdminActions, WithPagination;

    public string $search = '';

    public ?int $viewingId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleView(int $id): void
    {
        $this->viewingId = $this->viewingId === $id ? null : $id;
    }

    public function confirmRestore(int $id): void
    {
        $this->requestConfirm('restore', [$id], [
            'title' => __('msg_deleted_models.confirm_restore_title'),
            'message' => __('msg_deleted_models.confirm_restore_message'),
            'label' => __('msg_deleted_models.confirm_restore_label'),
        ]);
    }

    public function confirmForceDelete(int $id): void
    {
        $this->requestConfirm('forceDelete', [$id], [
            'title' => __('msg_deleted_models.confirm_force_delete_title'),
            'message' => __('msg_deleted_models.confirm_force_delete_message'),
            'label' => __('msg_deleted_models.confirm_force_delete_label'),
            'danger' => true,
        ]);
    }

    public function restore(int $id): void
    {
        $record = DeletedModel::query()->findOrFail($id);

        try {
            $record->model::restore($record->key);
            $this->notify(__('msg_deleted_models.restored_success'));
        } catch (Throwable) {
            $this->notify(__('msg_deleted_models.restore_failed'), 'error');
        }
    }

    public function forceDelete(int $id): void
    {
        DeletedModel::query()->findOrFail($id)->delete();
        $this->notify(__('msg_deleted_models.force_deleted_success'));
    }

    public function render(): Factory|View
    {
        $records = DeletedModel::query()
            ->when($this->search, fn ($q) => $q->where('model', 'like', "%{$this->search}%"))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.admin.deleted-models.index', [
            'records' => $records,
        ]);
    }
}
