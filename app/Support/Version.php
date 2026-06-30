<?php

declare(strict_types=1);

namespace App\Support;

class Version
{
    /**
     * Versão atual do projeto.
     *
     * O deploy grava a tag git mais recente no arquivo VERSION (na raiz),
     * de modo que a versão se atualiza automaticamente a cada release.
     * Em ambientes sem o arquivo, cai para APP_VERSION ou "dev".
     */
    public static function current(): string
    {
        $file = base_path('VERSION');

        if (is_file($file)) {
            $version = mb_trim((string) file_get_contents($file));

            if ($version !== '') {
                return mb_ltrim($version, 'v');
            }
        }

        return mb_ltrim((string) (config('app.version') ?: 'dev'), 'v');
    }
}
