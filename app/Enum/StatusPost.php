<?php

namespace App\Enum;

enum StatusPost: int
{
    case PUBLISHED = 1;
    case PENDING = 2;
    case INACTIVE = 3;

    public function label(): string
    {
        return match ($this) {
            self::PUBLISHED => __('Published'),
            self::PENDING => __('Pending'),
            self::INACTIVE => __('Inactive'),
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
}
