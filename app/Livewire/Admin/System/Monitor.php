<?php

declare(strict_types=1);

namespace App\Livewire\Admin\System;

use App\Livewire\Admin\Support\HasAdminActions;
use App\Livewire\Admin\Support\WithDataTable;
use App\Services\System\DebugbarViewer;
use App\Services\System\LogViewer;
use App\Services\System\QueueInspector;
use App\Services\System\ScheduleInspector;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Pagination\Paginator as PaginatorResolver;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Página de observabilidade do sistema (apenas super-admin): logs de arquivo,
 * capturas do Debugbar, tarefas agendadas e filas (jobs pendentes/falhos) do
 * banco. Reúne tudo em abas para dar controle sem acesso ao servidor.
 *
 * Cada aba exibe UMA tabela por vez (a de jobs alterna entre pendentes/falhos),
 * então todas compartilham o mesmo estado de busca/ordenação/paginação do
 * trait WithDataTable, reaproveitando os componentes padrão do admin.
 */
#[Layout('components.layouts.admin')]
#[Title('Sistema')]
class Monitor extends Component
{
    use HasAdminActions, WithDataTable, WithPagination;

    /** Aba ativa: logs | debug | schedules | jobs. */
    #[Url]
    public string $tab = 'logs';

    // --- Logs (arquivo) -----------------------------------------------------

    #[Url]
    public string $logFile = '';

    public string $logLevel = '';

    // --- Debugbar -----------------------------------------------------------

    public ?string $captureId = null;

    // --- Jobs / filas -------------------------------------------------------

    /** Sub-visão da aba de filas: pending | failed. */
    public string $jobsView = 'pending';

    public function mount(): void
    {
        [$this->sortField, $this->sortDirection] = $this->defaultSort();
    }

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['logs', 'debug', 'schedules', 'jobs'], true) ? $tab : 'logs';

        // Fecha qualquer captura aberta e zera os filtros ao trocar de aba.
        $this->captureId = null;
        $this->search = '';
        $this->logLevel = '';
        [$this->sortField, $this->sortDirection] = $this->defaultSort();
        $this->resetPage();
    }

    public function setJobsView(string $view): void
    {
        $this->jobsView = in_array($view, ['pending', 'failed'], true) ? $view : 'pending';
        $this->search = '';
        [$this->sortField, $this->sortDirection] = $this->defaultSort();
        $this->resetPage();
    }

    public function selectLog(string $file): void
    {
        $this->logFile = $file;
        $this->logLevel = '';
        $this->search = '';
        $this->resetPage();
    }

    public function updatingLogLevel(): void
    {
        $this->resetPage();
    }

    public function confirmClearLog(string $file): void
    {
        $this->requestConfirm('clearLog', [$file], [
            'title' => __('crud_system.confirm_clear_log_title'),
            'message' => __('crud_system.confirm_clear_log_message'),
            'label' => __('crud_system.confirm_clear_log_label'),
            'danger' => true,
        ]);
    }

    public function clearLog(string $file): void
    {
        resolve(LogViewer::class)->clear($file)
            ? $this->notify(__('crud_system.log_cleared'))
            : $this->notify(__('crud_system.log_clear_failed'), 'error');
    }

    // --- Debugbar -----------------------------------------------------------

    public function showCapture(string $id): void
    {
        $this->captureId = $id;
    }

    public function closeCapture(): void
    {
        $this->captureId = null;
    }

    public function confirmClearDebug(): void
    {
        $this->requestConfirm('clearDebug', [], [
            'title' => __('crud_system.confirm_clear_debug_title'),
            'message' => __('crud_system.confirm_clear_debug_message'),
            'label' => __('crud_system.confirm_clear_debug_label'),
            'danger' => true,
        ]);
    }

    public function clearDebug(): void
    {
        $count = resolve(DebugbarViewer::class)->clear();
        $this->captureId = null;
        $this->notify(__('crud_system.debug_cleared', ['count' => $count]));
    }

    // --- Jobs / filas -------------------------------------------------------

    public function retryJob(string $uuid): void
    {
        resolve(QueueInspector::class)->retry($uuid);
        $this->notify(__('crud_system.job_retried'));
    }

    public function retryAllFailed(): void
    {
        resolve(QueueInspector::class)->retryAll();
        $this->notify(__('crud_system.jobs_retried_all'));
    }

    public function confirmForgetFailed(string $uuid): void
    {
        $this->requestConfirm('forgetFailed', [$uuid], [
            'title' => __('crud_system.confirm_forget_title'),
            'message' => __('crud_system.confirm_forget_message'),
            'label' => __('crud_system.confirm_forget_label'),
            'danger' => true,
        ]);
    }

    public function forgetFailed(string $uuid): void
    {
        resolve(QueueInspector::class)->forget($uuid);
        $this->notify(__('crud_system.job_forgotten'));
    }

    public function confirmFlushFailed(): void
    {
        $this->requestConfirm('flushFailed', [], [
            'title' => __('crud_system.confirm_flush_title'),
            'message' => __('crud_system.confirm_flush_message'),
            'label' => __('crud_system.confirm_flush_label'),
            'danger' => true,
        ]);
    }

    public function flushFailed(): void
    {
        resolve(QueueInspector::class)->flush();
        $this->notify(__('crud_system.jobs_flushed'));
    }

    public function render(): Factory|View
    {
        $logs = resolve(LogViewer::class);
        $files = $logs->files();

        // Seleção padrão: o log mais recente, se nenhum estiver escolhido.
        if ($this->logFile === '' && $files->isNotEmpty()) {
            $this->logFile = $files->first()['name'];
        }

        $queue = resolve(QueueInspector::class);

        return view('livewire.admin.system.monitor', [
            'logFiles' => $files,
            'logLevels' => LogViewer::LEVELS,
            'logEntries' => $this->tab === 'logs' && $this->logFile !== ''
                ? $this->tableFrom(collect($logs->entries($this->logFile, $this->logLevel)), ['message', 'context', 'level'])
                : $this->emptyPage(),
            'captures' => $this->tab === 'debug'
                ? $this->tableFrom(resolve(DebugbarViewer::class)->captures(), ['method', 'uri', 'status'])
                : $this->emptyPage(),
            'capture' => $this->captureId !== null ? resolve(DebugbarViewer::class)->show($this->captureId) : null,
            'schedules' => $this->tab === 'schedules'
                ? $this->tableFrom(resolve(ScheduleInspector::class)->events(), ['command', 'expression', 'human'])
                : $this->emptyPage(),
            'queueCounts' => $queue->counts(),
            'jobs' => $this->tab === 'jobs'
                ? ($this->jobsView === 'failed'
                    ? $queue->failed($this->search, $this->sortField, $this->sortDirection, $this->perPage)
                    : $queue->pending($this->search, $this->sortField, $this->sortDirection, $this->perPage))
                : null,
        ]);
    }

    /**
     * Colunas ordenáveis por aba (usado pelo WithDataTable::sortBy).
     *
     * @return array<int, string>
     */
    protected function sortableColumns(): array
    {
        return match ($this->tab) {
            'logs' => ['level', 'datetime'],
            'debug' => ['method', 'uri', 'status', 'duration', 'time'],
            'schedules' => ['command', 'expression', 'human', 'next_run'],
            'jobs' => $this->jobsView === 'failed'
                ? ['id', 'queue', 'failed_at']
                : ['id', 'queue', 'attempts', 'available_at'],
            default => ['id'],
        };
    }

    /**
     * Ordenação padrão da aba/visão ativa.
     *
     * @return array{0: string, 1: string}
     */
    private function defaultSort(): array
    {
        return match ($this->tab) {
            'logs' => ['datetime', 'desc'],
            'debug' => ['time', 'desc'],
            'schedules' => ['next_run', 'asc'],
            'jobs' => $this->jobsView === 'failed' ? ['failed_at', 'desc'] : ['id', 'desc'],
            default => ['id', 'desc'],
        };
    }

    /**
     * Aplica busca (nas colunas informadas), ordenação e paginação a uma
     * coleção de linhas (arrays associativos) — o equivalente em memória do
     * applyTable() usado nas tabelas de banco.
     *
     * @param  Collection<int, array<string, mixed>>  $items
     * @param  array<int, string>  $searchable
     * @return LengthAwarePaginator<int, array<string, mixed>>
     */
    private function tableFrom(Collection $items, array $searchable): LengthAwarePaginator
    {
        if ($this->search !== '') {
            $needle = mb_strtolower($this->search);
            $items = $items->filter(function (array $row) use ($searchable, $needle): bool {
                foreach ($searchable as $col) {
                    if (str_contains(mb_strtolower((string) ($row[$col] ?? '')), $needle)) {
                        return true;
                    }
                }

                return false;
            });
        }

        if (in_array($this->sortField, $this->sortableColumns(), true)) {
            $items = $this->sortDirection === 'asc'
                ? $items->sortBy($this->sortField, SORT_NATURAL | SORT_FLAG_CASE)
                : $items->sortByDesc($this->sortField, SORT_NATURAL | SORT_FLAG_CASE);
        }

        return $this->paginateItems($items->values());
    }

    /**
     * Pagina uma coleção respeitando o seletor de itens por página ("all"
     * mostra tudo numa página só).
     *
     * @param  Collection<int, array<string, mixed>>  $items
     * @return LengthAwarePaginator<int, array<string, mixed>>
     */
    private function paginateItems(Collection $items): LengthAwarePaginator
    {
        $page = PaginatorResolver::resolveCurrentPage();
        $perPage = $this->perPage === 'all' ? max(1, $items->count()) : max(1, (int) $this->perPage);

        return new Paginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => PaginatorResolver::resolveCurrentPath(), 'pageName' => 'page'],
        );
    }

    /**
     * @return LengthAwarePaginator<int, array<string, mixed>>
     */
    private function emptyPage(): LengthAwarePaginator
    {
        return new Paginator([], 0, max(1, (int) ($this->perPage === 'all' ? 10 : $this->perPage)));
    }
}
