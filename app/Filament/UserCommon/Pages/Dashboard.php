<?php

namespace App\Filament\UserCommon\Pages;

use App\Filament\UserCommon\Widgets\WelcomeUser;
use Filament\Pages\Page;

class Dashboard extends \Filament\Pages\Dashboard
{
    /**
     * @return bool
     */
    public static function canAccess() : bool
    {
        return auth()->check() && auth()->user()->hasRole('user');
    }

    public function getWidgets() : array
    {
        return [
            WelcomeUser::class,
        ];
    }
}
