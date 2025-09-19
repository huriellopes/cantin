<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\DailyVisitsChart;
use App\Filament\Admin\Widgets\Info2Widget;
use App\Filament\Admin\Widgets\InfosWidget;
use App\Filament\Admin\Widgets\PostsPerDayChart;
use App\Filament\Admin\Widgets\RegisteredUsersChart;
use App\Filament\Admin\Widgets\TerreirosPerDayChart;
use App\Filament\Admin\Widgets\TopLevelCommentsChart;
use App\Filament\UserCommon\Widgets\WelcomeUser;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Dashboard extends \Filament\Pages\Dashboard
{
    /**
     * @return bool
     */
    public static function canAccess() : bool
    {
        return Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin'));
    }

    public function getWidgets() : array
    {
        return [
            WelcomeUser::class,
            InfosWidget::class,
            Info2Widget::class,
            RegisteredUsersChart::class,
            DailyVisitsChart::class,
            PostsPerDayChart::class,
            TopLevelCommentsChart::class,
            TerreirosPerDayChart::class,
        ];
    }
}
