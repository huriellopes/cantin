<?php

namespace App\Filament\UserCommon\Widgets;

use Filament\Widgets\Widget;

class WelcomeUser extends Widget
{
    protected static string $view = 'filament.user-common.widgets.welcome-user';

    protected int | string | array $columnSpan = 2;
}
