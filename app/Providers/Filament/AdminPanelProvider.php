<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Dashboard;
use App\Http\Middleware\CheckImpersonateMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->darkMode(true)
            ->path('admin')
            ->favicon('../public/assets/images/cantin.ico')
            ->login(false)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->plugins([])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationItems([
                NavigationItem::make('Página Inicial')
                    ->group('Links Uteis')
                    ->sort(10)
                    ->url('/', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-link'),
                NavigationItem::make('Blog')
                    ->group('Links Uteis')
                    ->sort(10)
                    ->url('/blog', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-link'),
                NavigationItem::make('Terreiros')
                    ->group('Links Uteis')
                    ->sort(10)
                    ->url('/terreiros', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-link'),
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                // CheckImpersonateMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
