<?php

namespace App\Enums;

enum Status: int
{
    case ACTIVE = 1;

    case INACTIVE = 0;

    /**
     * @return string
     */
    public function label() : string
    {
        return match ($this) {
            self::ACTIVE => 'Ativo',
            self::INACTIVE => 'Inativo',
        };
    }
}
