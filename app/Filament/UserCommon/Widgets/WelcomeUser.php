<?php

namespace App\Filament\UserCommon\Widgets;

use Filament\Widgets\Widget;

class WelcomeUser extends Widget
{
    protected int | string | array $columnSpan = 2;

    protected string $view = 'filament.user-common.widgets.welcome-user';
}
