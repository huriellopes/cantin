<?php

declare(strict_types=1);

namespace App\Enum;

use BackedEnum;

enum Status: int
{
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => (string) __('common.active'),
            self::INACTIVE => (string) __('common.inactive'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
        };
    }

    public function getIcon(): string|BackedEnum|null
    {
        return match ($this) {
            self::ACTIVE => 'heroicon-o-check-circle',
            self::INACTIVE => 'heroicon-o-x-circle',
        };
    }
    case ACTIVE = 1;
    case INACTIVE = 0;
}
