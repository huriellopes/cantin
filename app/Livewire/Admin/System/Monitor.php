<?php

declare(strict_types=1);

namespace App\Livewire\Admin\System;

use App\Livewire\Admin\Support\HasAdminActions;
use App\Services\System\DebugbarViewer;
use App\Services\System\LogViewer;
use App\Services\System\QueueInspector;
use App\Services\System\ScheduleInspector;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Página de observabilidade do sistema (apenas super-admin): logs de arquivo,
 * capturas do Debugbar, tarefas agendadas e filas (jobs pendentes/falhos) do
 * banco. Reúne tudo em abas para dar controle sem acesso ao servidor.
 */
#[Layout('components.layouts.admin')]
#[Title('Sistema')]
class Monitor extends Component
{
    use HasAdminActions, WithPagination;

    /** Aba ativa: logs | debug | schedules | jobs. */
    #[Url]
    public string $tab = 'logs';

    // --- Logs (arquivo) -----------------------------------------------------

    #[Url]
    public string $logFile = '';

    public string $logLevel = '';

    public string $logSearch = '';

    // --- Debugbar -----------------------------------------------------------

    public ?string $captureId = null;

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['logs', 'debug', 'schedules', 'jobs'], true) ? $tab : 'logs';
        $this->resetPage('pendingPage');
        $this->resetPage('failedPage');
    }

    public function selectLog(string $file): void
    {
        $this->logFile = $file;
        $this->logLevel = '';
        $this->logSearch = '';
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
                ? $logs->entries($this->logFile, $this->logLevel, $this->logSearch)
                : [],
            'captures' => $this->tab === 'debug' ? resolve(DebugbarViewer::class)->captures() : collect(),
            'capture' => $this->captureId !== null ? resolve(DebugbarViewer::class)->show($this->captureId) : null,
            'schedules' => $this->tab === 'schedules' ? resolve(ScheduleInspector::class)->events() : collect(),
            'queueCounts' => $queue->counts(),
            'pendingJobs' => $this->tab === 'jobs' ? $queue->pending() : null,
            'failedJobs' => $this->tab === 'jobs' ? $queue->failed() : null,
        ]);
    }
}
