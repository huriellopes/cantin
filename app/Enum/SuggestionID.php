<?php

declare(strict_types=1);

namespace App\Enum;

enum SuggestionID: string
{
    case CRITICAS = '1';
    case DUVIDAS = '2';
    case INDICACOES = '3';

    public static function verifySuggestionId(string $option): ?true
    {
        return match ($option) {
            '1' => true,
            '2' => true,
            '3' => true,
            default => null,
        };
    }
}
