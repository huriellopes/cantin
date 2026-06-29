<?php

namespace App\Providers;

use App\Contracts\Address\IAddressService;
use App\Contracts\BotWebhook\IBotService;
use App\Models\Category;
use App\Models\CommonQuestion;
use App\Models\Page;
use App\Models\PartnerEntity;
use App\Models\StaticPage;
use App\Observers\CategoryObserver;
use App\Observers\CommonQuestionObserver;
use App\Observers\PageObserver;
use App\Observers\PartnerEntityObserver;
use App\Observers\StaticPageObserver;
use App\Services\Address\ViaCepService;
use App\Services\BotWebhook\TelegramService;
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
        Category::observe(CategoryObserver::class);
        StaticPage::observe(StaticPageObserver::class);
        CommonQuestion::observe(CommonQuestionObserver::class);
        PartnerEntity::observe(PartnerEntityObserver::class);
        Page::observe(PageObserver::class);
        Paginator::useTailwind();
        Model::unguard();
        //        Model::preventLazyLoading();
    }
}
