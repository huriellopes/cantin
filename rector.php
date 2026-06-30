<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        // Código morto a ser removido na refatoração — não vale processar
        __DIR__ . '/app/Livewire/Site',
        __DIR__ . '/app/Http/Routes/Web/SiteRoute.php',
    ])
    // Regras de qualidade de código PHP
    ->withPhpSets(php83: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        earlyReturn: true,
    )
    // Regras específicas de Laravel + alvo de upgrade para a versão mais recente
    ->withSets([
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_IF_HELPERS,
        LaravelSetList::LARAVEL_COLLECTION,
        LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
        // Upgrade incremental — habilitar conforme avança a migração para Laravel 13.
        // LaravelLevelSetList::UP_TO_LARAVEL_120,
    ]);
