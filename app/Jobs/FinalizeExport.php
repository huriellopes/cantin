<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Export;
use App\Support\TelegramNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinalizeExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $exportId) {}

    public function handle(): void
    {
        $export = Export::query()->find($this->exportId);

        if (!$export) {
            return;
        }

        $export->update(['status' => 'ready']);

        TelegramNotifier::send(
            "📦 <b>Exportação pronta</b>\n" .
            'Usuário: ' . e((string) ($export->user?->name ?? '#' . $export->user_id)) . "\n" .
            'Arquivo: <code>' . e($export->name) . '</code>',
        );
    }
}
