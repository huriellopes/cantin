<?php

declare(strict_types=1);

namespace App\Services\System;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Inspeciona as filas persistidas no banco: jobs pendentes (tabela `jobs`) e
 * jobs que falharam (tabela `failed_jobs`). Usado pela página do super-admin.
 */
class QueueInspector
{
    /**
     * Contagens resumidas para os cartões de status.
     *
     * @return array{pending: int, failed: int, connection: string}
     */
    public function counts(): array
    {
        return [
            'pending' => $this->hasTable('jobs') ? (int) DB::table('jobs')->count() : 0,
            'failed' => $this->hasTable('failed_jobs') ? (int) DB::table('failed_jobs')->count() : 0,
            'connection' => (string) config('queue.default'),
        ];
    }

    /**
     * Jobs pendentes: busca, ordenação e paginação.
     *
     * @return LengthAwarePaginator<int, object>
     */
    public function pending(string $search = '', string $sortField = 'id', string $sortDir = 'desc', int|string $perPage = 10): LengthAwarePaginator
    {
        if (!$this->hasTable('jobs')) {
            return $this->emptyPaginator($this->perPageInt($perPage, 0));
        }

        $query = DB::table('jobs');

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search): void {
                $q->where('queue', 'like', "%{$search}%")->orWhere('payload', 'like', "%{$search}%");
            });
        }

        $field = in_array($sortField, ['id', 'queue', 'attempts', 'available_at'], true) ? $sortField : 'id';
        $query->orderBy($field, $sortDir === 'asc' ? 'asc' : 'desc');

        $paginator = $query->paginate($this->perPageInt($perPage, (clone $query)->count()));

        $paginator->through(function (object $job): object {
            $payload = json_decode((string) $job->payload, true);

            $job->name = is_array($payload) ? ($payload['displayName'] ?? 'Job') : 'Job';
            $job->available_at_human = $job->available_at ? date('d/m/Y H:i:s', (int) $job->available_at) : null;
            $job->created_at_human = $job->created_at ? date('d/m/Y H:i:s', (int) $job->created_at) : null;

            return $job;
        });

        return $paginator;
    }

    /**
     * Jobs que falharam: busca, ordenação e paginação.
     *
     * @return LengthAwarePaginator<int, object>
     */
    public function failed(string $search = '', string $sortField = 'failed_at', string $sortDir = 'desc', int|string $perPage = 10): LengthAwarePaginator
    {
        if (!$this->hasTable('failed_jobs')) {
            return $this->emptyPaginator($this->perPageInt($perPage, 0));
        }

        $query = DB::table('failed_jobs');

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search): void {
                $q->where('queue', 'like', "%{$search}%")
                    ->orWhere('uuid', 'like', "%{$search}%")
                    ->orWhere('exception', 'like', "%{$search}%")
                    ->orWhere('payload', 'like', "%{$search}%");
            });
        }

        $field = in_array($sortField, ['id', 'queue', 'failed_at'], true) ? $sortField : 'failed_at';
        $query->orderBy($field, $sortDir === 'asc' ? 'asc' : 'desc');

        $paginator = $query->paginate($this->perPageInt($perPage, (clone $query)->count()));

        $paginator->through(function (object $job): object {
            $payload = json_decode((string) $job->payload, true);

            $job->name = is_array($payload) ? ($payload['displayName'] ?? 'Job') : 'Job';
            $job->exception_short = str($job->exception)->before("\n")->limit(180)->value();

            return $job;
        });

        return $paginator;
    }

    /**
     * Reenfileira um job que falhou (via artisan queue:retry).
     */
    public function retry(string $uuid): void
    {
        Artisan::call('queue:retry', ['id' => [$uuid]]);
    }

    /**
     * Reenfileira todos os jobs que falharam.
     */
    public function retryAll(): void
    {
        Artisan::call('queue:retry', ['id' => ['all']]);
    }

    /**
     * Remove um job que falhou do registro.
     */
    public function forget(string $uuid): void
    {
        Artisan::call('queue:forget', ['id' => $uuid]);
    }

    /**
     * Limpa todos os jobs que falharam.
     */
    public function flush(): void
    {
        Artisan::call('queue:flush');
    }

    /**
     * Converte o valor do seletor (int ou "all") no total por página.
     */
    private function perPageInt(int|string $perPage, int $total): int
    {
        return $perPage === 'all' ? max(1, $total) : max(1, (int) $perPage);
    }

    private function hasTable(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @return LengthAwarePaginator<int, object>
     */
    private function emptyPaginator(int $perPage): LengthAwarePaginator
    {
        return new Paginator([], 0, max(1, $perPage));
    }
}
