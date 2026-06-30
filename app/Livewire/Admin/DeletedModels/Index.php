<?php

namespace App\Livewire\Admin\DeletedModels;

use App\Livewire\Admin\Support\HasAdminActions;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\DeletedModels\Models\DeletedModel;

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
            'title' => 'Restaurar registro',
            'message' => 'Deseja restaurar este registro excluído?',
            'label' => 'Restaurar',
        ]);
    }

    public function confirmForceDelete(int $id): void
    {
        $this->requestConfirm('forceDelete', [$id], [
            'title' => 'Excluir permanentemente',
            'message' => 'Esta ação remove o registro definitivamente e não pode ser desfeita.',
            'label' => 'Excluir permanentemente',
            'danger' => true,
        ]);
    }

    public function restore(int $id): void
    {
        $record = DeletedModel::query()->findOrFail($id);

        try {
            $record->model::restore($record->key);
            $this->notify('Registro restaurado com sucesso.');
        } catch (\Throwable) {
            $this->notify('Não foi possível restaurar o registro.', 'error');
        }
    }

    public function forceDelete(int $id): void
    {
        DeletedModel::query()->findOrFail($id)->delete();
        $this->notify('Registro removido permanentemente.');
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
