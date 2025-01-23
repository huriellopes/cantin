<?php

namespace App\Enum;

enum LevelEnum : int
{
    case SUPER = 1;
    case ADMIN = 2;

    public function getName(): string
    {
        return match ($this)
        {
            self::SUPER => 'Super Usuário',
            self::ADMIN => 'Administrador (a)',
            default => 'Nível de acesso não encontrado.'
        };
    }
}
