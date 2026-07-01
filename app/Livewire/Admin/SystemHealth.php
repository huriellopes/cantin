<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Throwable;

/**
 * Cartão de saúde da aplicação (super-admin), com polling próprio para checagem
 * em tempo real — independente do refresh do restante do dashboard.
 */
class SystemHealth extends Component
{
    public function mount(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin() ?? false, 403);
    }

    public function render(): Factory|View
    {
        return view('livewire.admin.system-health', [
            'checks' => $this->checks(),
        ]);
    }

    /**
     * Verificações: banco, cache, filas e armazenamento. Status ok | warn | down.
     *
     * @return array<int, array{key: string, label: string, status: string, detail: string}>
     */
    private function checks(): array
    {
        $checks = [];

        try {
            DB::connection()->select('select 1');
            $checks[] = ['key' => 'database', 'label' => __('msg_dashboard.health_database'), 'status' => 'ok', 'detail' => (string) config('database.default')];
        } catch (Throwable) {
            $checks[] = ['key' => 'database', 'label' => __('msg_dashboard.health_database'), 'status' => 'down', 'detail' => '—'];
        }

        try {
            Cache::put('health:ping', 'ok', 10);
            $ok = Cache::get('health:ping') === 'ok';
            $checks[] = ['key' => 'cache', 'label' => __('msg_dashboard.health_cache'), 'status' => $ok ? 'ok' : 'warn', 'detail' => (string) config('cache.default')];
        } catch (Throwable) {
            $checks[] = ['key' => 'cache', 'label' => __('msg_dashboard.health_cache'), 'status' => 'down', 'detail' => '—'];
        }

        try {
            $failed = Schema::hasTable('failed_jobs') ? (int) DB::table('failed_jobs')->count() : 0;
            $checks[] = ['key' => 'queue', 'label' => __('msg_dashboard.health_queue'), 'status' => $failed > 0 ? 'warn' : 'ok', 'detail' => $failed > 0 ? __('msg_dashboard.health_failed_jobs', ['count' => $failed]) : (string) config('queue.default')];
        } catch (Throwable) {
            $checks[] = ['key' => 'queue', 'label' => __('msg_dashboard.health_queue'), 'status' => 'down', 'detail' => '—'];
        }

        $writable = is_writable(storage_path('framework')) && is_writable(storage_path('logs'));
        $checks[] = ['key' => 'storage', 'label' => __('msg_dashboard.health_storage'), 'status' => $writable ? 'ok' : 'down', 'detail' => $writable ? 'OK' : '—'];

        return $checks;
    }
}
