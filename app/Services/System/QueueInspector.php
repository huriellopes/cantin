<?php

declare(strict_types=1);

namespace App\Services\System;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * Jobs pendentes, paginados e decodificados para exibição.
     *
     * @return LengthAwarePaginator<int, object>
     */
    public function pending(int $perPage = 15, string $pageName = 'pendingPage'): LengthAwarePaginator
    {
        if (!$this->hasTable('jobs')) {
            return $this->emptyPaginator($perPage);
        }

        $paginator = DB::table('jobs')->orderByDesc('id')->paginate($perPage, ['*'], $pageName);

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
     * Jobs que falharam, paginados e decodificados.
     *
     * @return LengthAwarePaginator<int, object>
     */
    public function failed(int $perPage = 15, string $pageName = 'failedPage'): LengthAwarePaginator
    {
        if (!$this->hasTable('failed_jobs')) {
            return $this->emptyPaginator($perPage);
        }

        $paginator = DB::table('failed_jobs')->orderByDesc('id')->paginate($perPage, ['*'], $pageName);

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
        return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
    }
}
