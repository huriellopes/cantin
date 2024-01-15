<?php

namespace App\Archicture\Entities\Status\Enum;

/**
 * Class StatusEnum
 * @package App\Archicture\Entities\Status\Enum
 */
enum StatusEnum : int
{
    case ACTIVE = 1;
    case PENDING = 2;
    case INACTIVE = 3;
    case EXCLUDED = 4;

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Ativo',
            self::PENDING => 'Pendente',
            self::INACTIVE => 'Inativo',
            self::EXCLUDED => 'Excluído',
        };
    }
}
