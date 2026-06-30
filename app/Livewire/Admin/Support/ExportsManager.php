<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Support;

use App\Models\Export;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ExportsManager extends Component
{
    /**
     * Avisa (toaster) sobre exportações recém-concluídas e as marca como notificadas.
     * Chamado por wire:poll.
     */
    public function check(): void
    {
        $fresh = Export::query()
            ->where('user_id', Auth::id())
            ->where('status', 'ready')
            ->where('notified', false)
            ->get();

        foreach ($fresh as $export) {
            $this->dispatch('toast', type: 'success', message: __('exports.ready', ['name' => $export->name]));
            $export->update(['notified' => true]);
        }
    }

    public function render(): Factory|View
    {
        $exports = Export::query()
            ->where('user_id', Auth::id())
            ->where('status', 'ready')
            ->whereNull('downloaded_at')
            ->orderByDesc('id')
            ->get();

        return view('livewire.admin.support.exports-manager', [
            'exports' => $exports,
        ]);
    }
}
