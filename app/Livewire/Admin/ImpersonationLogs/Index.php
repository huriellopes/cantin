<?php

declare(strict_types=1);

namespace App\Livewire\Admin\ImpersonationLogs;

use App\Exports\ImpersonationLogsExport;
use App\Livewire\Admin\Support\WithDataTable;
use App\Models\ImpersonationLog;
use App\Support\ExportManager;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Logs de personificação')]
class Index extends Component
{
    use WithDataTable, WithPagination;

    public function export(): void
    {
        ExportManager::dispatch(ImpersonationLogsExport::class, __('crud_impersonation_logs.title'));
        $this->dispatch('toast', type: 'info', message: __('exports.started'));
    }

    public function render(): Factory|View
    {
        $logs = $this->applyTable(
            ImpersonationLog::query()->with(['impersonator:id,name,email', 'impersonated:id,name,email']),
            ['action', 'ip'],
        );

        return view('livewire.admin.impersonation-logs.index', [
            'logs' => $logs,
        ]);
    }

    /** @return array<int, string> */
    protected function sortableColumns(): array
    {
        return ['id', 'action', 'ip', 'created_at'];
    }
}
