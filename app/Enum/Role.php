<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;

enum Role : int implements HasColor
{
    case SUPER = 1;
    case ADMIN = 2;
    case USER = 3;

    public function label(): string
    {
        return match ($this)
        {
            self::SUPER => 'Super Usuário',
            self::ADMIN => 'Administrador(a)',
            self::USER => 'Usuário(a)',
            default => 'Nível de acesso não encontrado.'
        };
    }

    /**
     * @return string|array|string[]|null
     */
    public function getColor(): string|array|null
    {
        return match($this) {
            self::SUPER => 'success',
            self::ADMIN => 'warning',
            self::USER => 'primary',
        };
    }
}
