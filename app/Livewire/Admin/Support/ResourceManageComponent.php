<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Support;

use App\Enum\Status;
use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Base reutilizável para a PÁGINA dedicada de criação/edição de recursos
 * simples (campos texto/textarea/richtext + status opcional). Espelha o
 * ResourceComponent (que lista), mas em vez de modal usa uma página própria e
 * redireciona para a listagem ao salvar. Subclasses definem model, campos,
 * rótulos e a rota de retorno.
 */
#[Layout('components.layouts.admin')]
abstract class ResourceManageComponent extends Component
{
    public ?int $editingId = null;

    /** @var array<string, mixed> */
    public array $form = [];

    public function save(): void
    {
        $this->validate();

        $data = $this->form;

        // Sanitiza os campos de conteúdo rico (defesa contra XSS armazenado).
        foreach ($this->fields() as $name => $cfg) {
            if (($cfg['type'] ?? null) === 'richtext' && array_key_exists($name, $data)) {
                $data[$name] = HtmlSanitizer::clean((string) $data[$name]);
            }
        }

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

        session()->flash('toast', [
            'type' => 'success',
            'message' => $editing ? __('msg_resource.updated', ['name' => $this->singular()]) : __('msg_resource.created', ['name' => $this->singular()]),
        ]);

        $this->redirectRoute($this->indexRoute());
    }

    public function render()
    {
        return view('livewire.admin.resource-manage', [
            'fields' => $this->fields(),
            'singular' => $this->singular(),
            'indexRoute' => $this->indexRoute(),
        ]);
    }

    /**
     * Carrega o registro (edição) ou inicializa o formulário vazio (criação).
     */
    protected function initRecord(?Model $record): void
    {
        if ($record instanceof Model && $record->exists) {
            $this->editingId = (int) $record->getKey();
            $this->form = collect(array_keys($this->fields()))
                ->mapWithKeys(fn ($field): array => [$field => $record->{$field}])
                ->all();

            return;
        }

        $this->form = array_fill_keys(array_keys($this->fields()), '');
    }

    /** @return class-string<Model> */
    abstract protected function model(): string;

    /**
     * @return array<string, array{label?: string, type?: string, rules?: array<int, mixed>, unique?: bool}>
     */
    abstract protected function fields(): array;

    abstract protected function singular(): string;

    /** Nome da rota da listagem (para voltar/redirecionar). */
    abstract protected function indexRoute(): string;

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
