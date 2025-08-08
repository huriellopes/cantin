<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;

enum StatusPost : int implements HasColor
{
    case PUBLISHED = 1;
    case PENDING = 2;
    case INACTIVE = 3;

    /**
     * @return string
     */
    public function label() : string
    {
        return match ($this) {
            self::PUBLISHED => __('Published'),
            self::PENDING => __('Pending'),
            self::INACTIVE => __('Inactive'),
        };
    }

    /**
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PUBLISHED => 'success',
            self::PENDING => 'warning',
            self::INACTIVE => 'danger',
        };
    }
}
