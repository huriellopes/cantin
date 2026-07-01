<?php

declare(strict_types=1);

namespace App\Services\System;

use Illuminate\Support\Collection;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Lê e interpreta os arquivos de log do Laravel em storage/logs.
 *
 * Usado pela página de observabilidade (apenas super-admin) para inspecionar
 * erros/debug diretamente dos arquivos, sem acesso ao servidor.
 */
class LogViewer
{
    /** Nível/severidade das linhas de log, para filtro e coloração. */
    public const array LEVELS = [
        'emergency', 'alert', 'critical', 'error',
        'warning', 'notice', 'info', 'debug',
    ];

    /** Limite de entradas lidas por arquivo (protege memória em logs grandes). */
    private const int MAX_ENTRIES = 2000;

    /**
     * Lista os arquivos .log disponíveis, do mais recente para o mais antigo.
     *
     * @return Collection<int, array{name: string, size: int, modified: int}>
     */
    public function files(): Collection
    {
        $dir = storage_path('logs');

        if (!is_dir($dir)) {
            return collect();
        }

        $finder = Finder::create()->files()->name('*.log')->in($dir)->depth(0);

        return collect(iterator_to_array($finder, false))
            ->map(fn (SplFileInfo $file): array => [
                'name' => $file->getFilename(),
                'size' => (int) $file->getSize(),
                'modified' => (int) $file->getMTime(),
            ])
            ->sortByDesc('modified')
            ->values();
    }

    /**
     * Lê as entradas de um arquivo de log (mais recentes primeiro), com filtro
     * opcional por nível e por texto.
     *
     * @return array<int, array{level: string, datetime: string, env: string, message: string, context: string}>
     */
    public function entries(string $file, string $level = '', string $search = ''): array
    {
        $path = $this->safePath($file);

        if ($path === null || !is_file($path)) {
            return [];
        }

        $content = (string) file_get_contents($path);
        $entries = $this->parse($content);

        $level = mb_strtolower(mb_trim($level));
        $search = mb_trim($search);

        return collect($entries)
            ->when($level !== '', fn (Collection $c): Collection => $c->filter(
                fn (array $e): bool => $e['level'] === $level,
            ))
            ->when($search !== '', fn (Collection $c): Collection => $c->filter(
                fn (array $e): bool => mb_stripos($e['message'] . $e['context'], $search) !== false,
            ))
            ->reverse()
            ->values()
            ->all();
    }

    /**
     * Esvazia (trunca) o conteúdo de um arquivo de log, mantendo o arquivo.
     */
    public function clear(string $file): bool
    {
        $path = $this->safePath($file);

        if ($path === null || !is_file($path)) {
            return false;
        }

        return file_put_contents($path, '') !== false;
    }

    /**
     * Divide o conteúdo bruto em entradas estruturadas. Cada entrada começa
     * numa linha "[YYYY-MM-DD HH:MM:SS] env.LEVEL: mensagem" e pode ter várias
     * linhas de stack trace/contexto até a próxima entrada.
     *
     * @return array<int, array{level: string, datetime: string, env: string, message: string, context: string}>
     */
    private function parse(string $content): array
    {
        $pattern = '/^\[(?<datetime>\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:[+-]\d{2}:?\d{2})?)\]\s+(?<env>[\w-]+)\.(?<level>[A-Z]+):/m';

        if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE) === false) {
            return [];
        }

        $entries = [];
        $count = count($matches[0]);

        for ($i = 0; $i < $count; $i++) {
            $start = $matches[0][$i][1];
            $end = $i + 1 < $count ? $matches[0][$i + 1][1] : mb_strlen($content);

            $block = mb_substr($content, $start, $end - $start);
            $headerLen = mb_strlen($matches[0][$i][0]);
            $body = mb_trim(mb_substr($block, $headerLen));

            // Separa a primeira linha (mensagem) do restante (stack/contexto).
            $lines = explode("\n", $body, 2);

            $entries[] = [
                'level' => mb_strtolower($matches['level'][$i][0]),
                'datetime' => $matches['datetime'][$i][0],
                'env' => $matches['env'][$i][0],
                'message' => mb_trim($lines[0]),
                'context' => isset($lines[1]) ? mb_trim($lines[1]) : '',
            ];

            if (count($entries) >= self::MAX_ENTRIES) {
                break;
            }
        }

        return $entries;
    }

    /**
     * Resolve o caminho absoluto de um arquivo de log garantindo que ele está
     * dentro de storage/logs (evita path traversal via o nome recebido).
     */
    private function safePath(string $file): ?string
    {
        $base = storage_path('logs');
        $name = basename($file);

        if ($name === '' || !str_ends_with($name, '.log')) {
            return null;
        }

        $path = $base . DIRECTORY_SEPARATOR . $name;
        $real = realpath($path);

        if ($real === false) {
            return null;
        }

        return str_starts_with($real, (string) realpath($base)) ? $real : null;
    }
}
