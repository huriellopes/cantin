<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Audits;

use App\Livewire\Admin\Support\WithDataTable;
use App\Models\Audit;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Visualização das auditorias (owen-it/laravel-auditing) para o super-admin.
 * Lista com o componente de tabela + modal com o detalhe (antes/depois).
 */
#[Layout('components.layouts.admin')]
#[Title('Auditorias')]
class Index extends Component
{
    use WithDataTable, WithPagination;

    public bool $showModal = false;

    /** @var array<int, array{field: string, old: string, new: string}> */
    public array $modified = [];

    /** @var array<string, mixed>|null */
    public ?array $auditMeta = null;

    public function view(int $id): void
    {
        $audit = Audit::query()->with('user:id,name,email')->findOrFail($id);

        /** @var array<string, mixed> $old */
        $old = $audit->old_values ?? [];
        /** @var array<string, mixed> $new */
        $new = $audit->new_values ?? [];

        $this->modified = collect(array_keys($old + $new))
            ->map(fn (string $field): array => [
                'field' => $field,
                'old' => $this->stringify($old[$field] ?? null),
                'new' => $this->stringify($new[$field] ?? null),
            ])
            ->all();

        $this->auditMeta = [
            'event' => $audit->event,
            'type' => class_basename((string) $audit->auditable_type),
            'id' => $audit->auditable_id,
            'user' => $audit->user instanceof User ? $audit->user->name : '—',
            'date' => $audit->created_at?->format('d/m/Y H:i:s'),
            'url' => $audit->url,
            'ip' => $audit->ip_address,
            'user_agent' => $audit->user_agent,
        ];

        $this->showModal = true;
    }

    public function render(): Factory|View
    {
        $audits = $this->applyTable(
            Audit::query()->with('user:id,name,email'),
            ['event', 'auditable_type'],
        );

        return view('livewire.admin.audits.index', [
            'audits' => $audits,
        ]);
    }

    /** @return array<int, string> */
    protected function sortableColumns(): array
    {
        return ['id', 'event', 'auditable_type', 'auditable_id', 'created_at'];
    }

    private function stringify(mixed $value): string
    {
        if (is_null($value) || $value === '') {
            return '—';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value)) {
            return (string) json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return (string) $value;
    }
}
