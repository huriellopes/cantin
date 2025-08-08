<?php

namespace App\Enum;

enum Role : int
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
}
