<?php

declare(strict_types=1);

namespace App\Enum;

enum Role: int
{
    public function label(): string
    {
        return match ($this) {
            self::SUPER => (string) __('admin.roles.super'),
            self::ADMIN => (string) __('admin.roles.admin'),
            self::USER => (string) __('admin.roles.user'),
            default => (string) __('admin.roles.unknown'),
        };
    }

    /**
     * @return string|array|string[]|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SUPER => 'success',
            self::ADMIN => 'warning',
            self::USER => 'primary',
        };
    }
    case SUPER = 1;
    case ADMIN = 2;
    case USER = 3;
}
