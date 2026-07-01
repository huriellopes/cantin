<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

/**
 * Remove arquivos antigos de log e capturas do Debugbar, mantendo apenas os
 * dos últimos N dias (padrão: 3). Executado pelo agendador para evitar que o
 * diretório storage cresça indefinidamente.
 */
class PruneSystemLogs extends Command
{
    protected $signature = 'system:prune-logs {--days=3 : Quantidade de dias a manter} {--dry-run : Apenas lista o que seria removido}';

    protected $description = 'Remove logs e capturas de debug antigos, mantendo os últimos N dias (padrão: 3).';

    public function handle(): int
    {
        $days = max(0, (int) $this->option('days'));
        $dryRun = (bool) $this->option('dry-run');

        // Corte no início do dia limite: arquivos modificados antes disso saem.
        $cutoff = now()->subDays($days)->startOfDay()->getTimestamp();

        $targets = [
            'logs' => ['dir' => storage_path('logs'), 'pattern' => '*.log'],
            'debugbar' => ['dir' => storage_path('debugbar'), 'pattern' => '*.json'],
        ];

        $totalRemoved = 0;
        $totalBytes = 0;

        foreach ($targets as $label => $target) {
            if (!is_dir($target['dir'])) {
                continue;
            }

            $finder = Finder::create()
                ->files()
                ->name($target['pattern'])
                ->in($target['dir'])
                ->depth(0)
                ->date('< @' . $cutoff);

            $removed = 0;
            $bytes = 0;

            foreach ($finder as $file) {
                $bytes += (int) $file->getSize();

                if ($dryRun) {
                    $this->line("  [dry-run] {$file->getFilename()}");
                    $removed++;

                    continue;
                }

                if (@unlink($file->getRealPath())) {
                    $removed++;
                }
            }

            $totalRemoved += $removed;
            $totalBytes += $bytes;

            $this->info(sprintf('%s: %d arquivo(s) (%s KB).', $label, $removed, number_format($bytes / 1024, 0)));
        }

        $verb = $dryRun ? 'seriam removidos' : 'removidos';
        $this->info(sprintf(
            'Concluído: %d arquivo(s) %s (%s KB), mantendo os últimos %d dia(s).',
            $totalRemoved,
            $verb,
            number_format($totalBytes / 1024, 0),
            $days,
        ));

        return self::SUCCESS;
    }
}
