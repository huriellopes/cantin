<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Address\IAddressService;
use App\Contracts\BotWebhook\IBotService;
use App\Models\Category;
use App\Models\Page;
use App\Models\PartnerEntity;
use App\Models\StaticPage;
use App\Observers\CategoryObserver;
use App\Observers\PageObserver;
use App\Observers\PartnerEntityObserver;
use App\Observers\StaticPageObserver;
use App\Services\Address\ViaCepService;
use App\Services\BotWebhook\TelegramService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Override;
use Spatie\SchemaOrg\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->singleton(
            IAddressService::class,
            ViaCepService::class,
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
        PartnerEntity::observe(PartnerEntityObserver::class);
        Page::observe(PageObserver::class);
        Paginator::useTailwind();
        // Mass-assignment protegido pelo $fillable de cada model (sem unguard).
        // Fora de produção, qualquer atributo fora do fillable lança erro
        // (revela mismatch nos testes); em produção é apenas ignorado (não quebra).
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());
        //        Model::preventLazyLoading();

        $this->bootSeo();
    }

    /**
     * Define os valores padrão de SEO (meta tags/OpenGraph/Twitter) e o
     * JSON-LD global da Organização. Cada página Livewire sobrescreve os
     * valores específicos via helper seo().
     */
    private function bootSeo(): void
    {
        seo()
            ->site(config('app.name'))
            ->title(default: config('app.name') . ' — Cadastro Nacional de Terreiros Inclusivos')
            ->description(default: 'CaNTIn — Cadastro Nacional de Terreiros Inclusivos: encontre e cadastre terreiros acolhedores e seguros para pessoas LGBTQIA+ em todo o Brasil.')
            ->image(default: asset('assets/images/CANTIn.png'))
            ->locale(str_replace('-', '_', app()->getLocale()))
            ->twitter()
            ->withUrl();

        // Título/descrição por página (rotas fixas do site). As páginas com
        // conteúdo dinâmico (blog e páginas estáticas) sobrescrevem no mount().
        Event::listen(RouteMatched::class, function (RouteMatched $event): void {
            $meta = [
                'site.home' => ['CaNTIn — Cadastro Nacional de Terreiros Inclusivos', 'Encontre e cadastre terreiros acolhedores e seguros para pessoas LGBTQIA+ em todo o Brasil.'],
                'site.about' => ['Sobre o CaNTIn', 'Conheça a missão do CaNTIn: mapear e fortalecer terreiros inclusivos de religiões de matriz africana.'],
                'site.terreiros.search' => ['Terreiros Inclusivos', 'Busque terreiros inclusivos e acolhedores de religiões de matriz africana em todo o Brasil.'],
                'site.terreiros.create' => ['Cadastrar Terreiro', 'Cadastre seu terreiro no CaNTIn e faça parte da rede nacional de terreiros inclusivos.'],
                'site.partners-entities' => ['Entidades Parceiras', 'Conheça as entidades parceiras que apoiam o CaNTIn e a luta por inclusão e diversidade.'],
                'site.trans-people' => ['Pessoas Trans', 'Acolhimento e recursos para pessoas trans nas religiões de matriz africana.'],
                'site.blog.posts' => ['Blog', 'Artigos e notícias sobre religiões de matriz africana, inclusão, diversidade e direitos.'],
                'site.links.external' => ['Links Úteis', 'Links e recursos úteis selecionados pelo CaNTIn para a comunidade.'],
                'site.guidelines' => ['Diretrizes', 'Diretrizes de uso e conduta da plataforma CaNTIn.'],
                'site.privacy' => ['Política de Privacidade', 'Saiba como o CaNTIn trata e protege os seus dados pessoais.'],
            ];

            $name = $event->route->getName();

            if (isset($meta[$name])) {
                [$title, $description] = $meta[$name];
                seo()->title($title)->description($description);
            }
        });

        View::share('organizationJsonLd', Schema::organization()
            ->name(config('app.name'))
            ->url(url('/'))
            ->logo(asset('assets/images/CANTIn.png'))
            ->description('CaNTIn — Cadastro Nacional de Terreiros Inclusivos.')
            ->toScript());
    }
}
