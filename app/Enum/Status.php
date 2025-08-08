<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;

enum Status: int implements HasColor
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

    /**
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
        };
    }
}
