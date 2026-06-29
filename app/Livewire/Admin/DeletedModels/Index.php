<?php

namespace App\Livewire\Admin\DeletedModels;

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
    use WithPagination;

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

    public function restore(int $id): void
    {
        $record = DeletedModel::query()->findOrFail($id);

        try {
            $record->model::restore($record->key);
            session()->flash('status', 'Registro restaurado com sucesso.');
        } catch (\Throwable) {
            session()->flash('status', 'Não foi possível restaurar o registro.');
        }
    }

    public function forceDelete(int $id): void
    {
        DeletedModel::query()->findOrFail($id)->delete();
        session()->flash('status', 'Registro removido permanentemente.');
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
