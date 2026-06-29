<?php

namespace App\Livewire\Admin\Support;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Base reutilizável para CRUDs simples (campos texto/textarea + status opcional)
 * geridos por modal. Subclasses definem o model, os campos e os rótulos.
 */
#[Layout('components.layouts.admin')]
abstract class ResourceComponent extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    /** @var array<string, mixed> */
    public array $form = [];

    /** @return class-string<Model> */
    abstract protected function model(): string;

    /**
     * Campos do formulário/tabela.
     *
     * @return array<string, array{label?: string, type?: string, rules?: array<int, mixed>, unique?: bool}>
     */
    abstract protected function fields(): array;

    abstract protected function heading(): string;

    abstract protected function singular(): string;

    /** @return array<int, string> */
    protected function searchable(): array
    {
        return ['name'];
    }

    protected function hasStatus(): bool
    {
        return false;
    }

    /**
     * Atributos extra aplicados apenas na criação.
     *
     * @return array<string, mixed>
     */
    protected function onCreate(): array
    {
        return [];
    }

    protected function table(): string
    {
        return (new ($this->model()))->getTable();
    }

    protected function rules(): array
    {
        $rules = [];

        foreach ($this->fields() as $name => $cfg) {
            // slug é opcional: gerado a partir do nome quando vazio
            $default = $name === 'slug' ? ['nullable', 'string', 'max:255'] : ['required', 'string', 'max:255'];
            $fieldRules = $cfg['rules'] ?? $default;

            if ($cfg['unique'] ?? false) {
                $fieldRules[] = Rule::unique($this->table(), $name)->ignore($this->editingId);
            }

            $rules["form.{$name}"] = $fieldRules;
        }

        return $rules;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->editingId = null;
        $this->form = array_fill_keys(array_keys($this->fields()), '');
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $record = $this->model()::query()->findOrFail($id);
        $this->editingId = $record->id;
        $this->form = collect(array_keys($this->fields()))
            ->mapWithKeys(fn ($field) => [$field => $record->{$field}])
            ->all();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = $this->form;

        // slug automático a partir do nome quando vazio
        if (array_key_exists('slug', $this->fields()) && blank($data['slug'] ?? null) && filled($data['name'] ?? null)) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (! $this->editingId) {
            if ($this->hasStatus()) {
                $data['status'] = Status::ACTIVE;
            }

            $data = array_merge($data, $this->onCreate());
        }

        $this->model()::query()->updateOrCreate(['id' => $this->editingId], $data);

        $this->showModal = false;
        session()->flash('status', $this->editingId ? "{$this->singular()} atualizado(a)." : "{$this->singular()} criado(a).");
    }

    public function toggleStatus(int $id): void
    {
        if (! $this->hasStatus()) {
            return;
        }

        $record = $this->model()::query()->findOrFail($id);
        $record->update(['status' => $record->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE]);
    }

    public function delete(int $id): void
    {
        $this->model()::query()->findOrFail($id)->delete();
        session()->flash('status', "{$this->singular()} excluído(a).");
    }

    public function render()
    {
        $query = $this->model()::query();

        if ($this->search) {
            $query->where(function ($q) {
                foreach ($this->searchable() as $col) {
                    $q->orWhere($col, 'like', "%{$this->search}%");
                }
            });
        }

        return view('livewire.admin.resource', [
            'records' => $query->orderByDesc('id')->paginate(10),
            'fields' => $this->fields(),
            'heading' => $this->heading(),
            'singular' => $this->singular(),
            'hasStatus' => $this->hasStatus(),
        ]);
    }
}
