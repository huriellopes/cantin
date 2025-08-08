<?php

namespace App\Providers;

use App\Contracts\Address\IAddressService;
use App\Contracts\BotWebhook\IBotService;
use App\Services\Address\ViaCepService;
use App\Services\BotWebhook\TelegramService;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use App\Http\Responses\LogoutResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);

        $this->app->singleton(
            IAddressService::class,
            ViaCepService::class
        );

        $this->app->singleton(
            IBotService::class,
            TelegramService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        Model::unguard();
//        Model::preventLazyLoading();
    }
}
