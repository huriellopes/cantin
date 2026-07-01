<?php

declare(strict_types=1);

namespace App\Enum;

enum StatusPost: int
{
    public function label(): string
    {
        return match ($this) {
            self::PUBLISHED => (string) __('common.published'),
            self::PENDING => (string) __('common.pending'),
            self::INACTIVE => (string) __('common.inactive'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PUBLISHED => 'success',
            self::PENDING => 'warning',
            self::INACTIVE => 'danger',
        };
    }
    case PUBLISHED = 1;
    case PENDING = 2;
    case INACTIVE = 3;
}
