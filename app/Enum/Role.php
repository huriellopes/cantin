<?php

declare(strict_types=1);

namespace App\Enum;

enum Role: int
{
    public function label(): string
    {
        return $this === self::SUPER
            ? (string) __('admin.roles.super')
            : (string) __('admin.roles.admin');
    }

    /**
     * @return string|array|string[]|null
     */
    public function getColor(): string|array|null
    {
        return $this === self::SUPER ? 'success' : 'warning';
    }
    case SUPER = 1;
    case ADMIN = 2;
}
