<?php

declare(strict_types=1);

namespace App\Services\System;

use Illuminate\Support\Collection;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Lê as capturas de requisição gravadas pelo Debugbar em storage/debugbar.
 *
 * Cada arquivo .json é o "storage" de uma requisição (rota, método, tempo,
 * consultas, exceções). Aqui expomos só um resumo para inspeção pelo super-admin.
 */
class DebugbarViewer
{
    /** Limite de capturas listadas (as mais recentes). */
    private const int MAX_ITEMS = 200;

    /**
     * Resumo das capturas mais recentes.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function captures(): Collection
    {
        if (!is_dir($this->dir())) {
            return collect();
        }

        $finder = Finder::create()->files()->name('*.json')->in($this->dir())->depth(0);

        return collect(iterator_to_array($finder, false))
            ->sortByDesc(fn (SplFileInfo $file): int => (int) $file->getMTime())
            ->take(self::MAX_ITEMS)
            ->map(fn (SplFileInfo $file): array => $this->summary($file))
            ->values();
    }

    /**
     * Conteúdo completo (decodificado) de uma captura, para inspeção detalhada.
     *
     * @return array<string, mixed>|null
     */
    public function show(string $id): ?array
    {
        $path = $this->safePath($id);

        if ($path === null || !is_file($path)) {
            return null;
        }

        $data = json_decode((string) file_get_contents($path), true);

        return is_array($data) ? $data : null;
    }

    /**
     * Remove todas as capturas do Debugbar.
     */
    public function clear(): int
    {
        if (!is_dir($this->dir())) {
            return 0;
        }

        $count = 0;

        foreach (Finder::create()->files()->name('*.json')->in($this->dir())->depth(0) as $file) {
            if (@unlink($file->getRealPath())) {
                $count++;
            }
        }

        return $count;
    }

    private function dir(): string
    {
        return storage_path('debugbar');
    }

    /**
     * @return array<string, mixed>
     */
    private function summary(SplFileInfo $file): array
    {
        $data = json_decode((string) file_get_contents($file->getRealPath()), true);
        $data = is_array($data) ? $data : [];

        $meta = $data['__meta'] ?? [];
        $request = $data['request']['data'] ?? [];
        $exceptions = $data['exceptions']['count'] ?? 0;

        return [
            'id' => $file->getBasename('.json'),
            'method' => (string) ($meta['method'] ?? $request['method'] ?? '—'),
            'uri' => (string) ($meta['uri'] ?? $request['uri'] ?? '—'),
            'time' => isset($meta['datetime']) ? (string) $meta['datetime'] : null,
            'duration' => isset($data['time']['duration']) ? (float) $data['time']['duration'] : null,
            'status' => isset($request['status_code']) ? (int) $request['status_code'] : null,
            'has_exceptions' => (int) $exceptions > 0,
            'modified' => (int) $file->getMTime(),
        ];
    }

    /**
     * Resolve o caminho de uma captura garantindo que fica dentro do diretório
     * do debugbar (evita path traversal via id recebido).
     */
    private function safePath(string $id): ?string
    {
        $name = basename($id);

        if ($name === '') {
            return null;
        }

        $path = $this->dir() . DIRECTORY_SEPARATOR . $name . '.json';
        $real = realpath($path);

        if ($real === false) {
            return null;
        }

        return str_starts_with($real, (string) realpath($this->dir())) ? $real : null;
    }
}
