<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Support;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Estado e helpers para tabelas: busca, itens por página e ordenação.
 *
 * Requer que a classe use Livewire\WithPagination.
 */
trait WithDataTable
{
    public string $search = '';

    public string $perPage = '10';

    public string $sortField = 'id';

    public string $sortDirection = 'desc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Opções do seletor de itens por página.
     *
     * @return array<int|string, string>
     */
    public function perPageOptions(): array
    {
        return [
            '10' => '10',
            '20' => '20',
            '30' => '30',
            '40' => '40',
            '50' => '50',
            '100' => '100',
            'all' => __('common.all'),
        ];
    }

    /**
     * Ordena por uma coluna (alterna a direção). Só aceita colunas permitidas.
     */
    public function sortBy(string $field): void
    {
        if (!in_array($field, $this->sortableColumns(), true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    /**
     * Colunas permitidas para ordenação (sobrescreva por componente).
     *
     * @return array<int, string>
     */
    protected function sortableColumns(): array
    {
        return ['id'];
    }

    /**
     * Aplica busca, ordenação e paginação a uma query base.
     *
     * @param  array<int, string>  $searchable
     */
    protected function applyTable(Builder $query, array $searchable): LengthAwarePaginator
    {
        if ($this->search !== '') {
            $query->where(function (Builder $q) use ($searchable): void {
                foreach ($searchable as $col) {
                    $q->orWhere($col, 'like', "%{$this->search}%");
                }
            });
        }

        $field = in_array($this->sortField, $this->sortableColumns(), true) ? $this->sortField : 'id';
        $direction = $this->sortDirection === 'asc' ? 'asc' : 'desc';
        $query->orderBy($field, $direction);

        $perPage = $this->perPage === 'all'
            ? max(1, (clone $query)->toBase()->getCountForPagination())
            : max(1, (int) $this->perPage);

        return $query->paginate($perPage);
    }
}
