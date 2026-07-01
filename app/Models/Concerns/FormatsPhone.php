<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Mantém o telefone SEM máscara no banco (só dígitos) e o exibe COM máscara.
 *
 * - set: remove tudo que não for dígito antes de gravar.
 * - get: formata a partir dos dígitos — (99) 9 9999-9999 (celular) ou
 *   (99) 9999-9999 (fixo). Também normaliza dados legados salvos com máscara.
 */
trait FormatsPhone
{
    protected function phone(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value): ?string {
                $digits = preg_replace('/\D/', '', (string) $value);

                return match (mb_strlen((string) $digits)) {
                    11 => sprintf('(%s) %s %s-%s', mb_substr($digits, 0, 2), mb_substr($digits, 2, 1), mb_substr($digits, 3, 4), mb_substr($digits, 7, 4)),
                    10 => sprintf('(%s) %s-%s', mb_substr($digits, 0, 2), mb_substr($digits, 2, 4), mb_substr($digits, 6, 4)),
                    default => $value === null ? null : (string) $value,
                };
            },
            set: fn (mixed $value): ?string => ($d = preg_replace('/\D/', '', (string) $value)) === '' ? null : $d,
        );
    }
}
