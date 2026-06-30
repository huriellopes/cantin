<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use Illuminate\Http\Response;

/**
 * Gera o robots.txt dinamicamente, sensível ao ambiente: em produção libera a
 * indexação (bloqueando apenas áreas privadas) e aponta o sitemap; em qualquer
 * outro ambiente (staging/local) bloqueia tudo para não vazar conteúdo de
 * homologação no índice do Google.
 */
class RobotsController
{
    public function __invoke(): Response
    {
        $lines = ['User-agent: *'];

        if (app()->isProduction()) {
            // Áreas privadas/sem valor de indexação.
            $lines[] = 'Disallow: /admin';
            $lines[] = 'Disallow: /login';
            $lines[] = 'Disallow: /impersonate';
            $lines[] = '';
            $lines[] = 'Sitemap: ' . url('/sitemap.xml');
        } else {
            $lines[] = 'Disallow: /';
        }

        return response(implode("\n", $lines) . "\n")
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
