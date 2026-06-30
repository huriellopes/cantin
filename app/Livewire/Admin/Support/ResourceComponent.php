<?php

declare(strict_types=1);

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
    use HasAdminActions, WithDataTable, WithPagination;

    public bool $showModal = false;

    public ?int $editingId = null;

    /** @var array<string, mixed> */
    public array $form = [];

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
            ->mapWithKeys(fn ($field): array => [$field => $record->{$field}])
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

        if (!$this->editingId) {
            if ($this->hasStatus()) {
                $data['status'] = Status::ACTIVE;
            }

            $data = array_merge($data, $this->onCreate());
        }

        $editing = (bool) $this->editingId;

        if ($editing) {
            $this->model()::query()->whereKey($this->editingId)->update($data);
        } else {
            $this->model()::query()->create($data);
        }

        $this->showModal = false;
        $this->notify($editing ? __('msg_resource.updated', ['name' => $this->singular()]) : __('msg_resource.created', ['name' => $this->singular()]));
    }

    public function view(int $id): void
    {
        $record = $this->model()::query()->findOrFail($id);

        $this->viewData = collect($this->fields())
            ->map(fn (array $cfg, string $name): array => [
                'label' => $cfg['label'] ?? Str::headline($name),
                'value' => strip_tags((string) $record->{$name}),
            ])
            ->values()
            ->all();

        $this->viewTitle = $this->singular();
        $this->showView = true;
    }

    public function toggleStatus(int $id): void
    {
        if (!$this->hasStatus()) {
            return;
        }

        $record = $this->model()::query()->findOrFail($id);
        $record->update(['status' => $record->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE]);
        $this->notify(__('msg_resource.status_updated'));
    }

    public function delete(int $id): void
    {
        $this->model()::query()->findOrFail($id)->delete();
        $this->notify(__('msg_resource.deleted', ['name' => $this->singular()]));
    }

    public function render()
    {
        $records = $this->applyTable($this->model()::query(), $this->searchable());

        return view('livewire.admin.resource', [
            'records' => $records,
            'fields' => $this->fields(),
            'heading' => $this->heading(),
            'singular' => $this->singular(),
            'hasStatus' => $this->hasStatus(),
        ]);
    }

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

    /** @return array<int, string> */
    protected function sortableColumns(): array
    {
        $columns = array_merge(['id'], array_keys($this->fields()));

        if ($this->hasStatus()) {
            $columns[] = 'status';
        }

        return $columns;
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
}
