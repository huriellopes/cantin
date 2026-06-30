<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enum\Status;
use App\Models\Post;
use App\Models\StaticPage;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

/**
 * Gera o public/sitemap.xml a partir das rotas públicas fixas + conteúdo
 * dinâmico (posts publicados e páginas estáticas ativas). Roda no agendador
 * (diariamente) e no deploy, mantendo o sitemap sempre atualizado para o Google.
 */
class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Gera o sitemap.xml com as rotas públicas e o conteúdo dinâmico (posts e páginas estáticas).';

    public function handle(): int
    {
        $sitemap = Sitemap::create();

        // Rotas públicas fixas → (nome da rota, prioridade, frequência).
        $staticRoutes = [
            ['site.home', 1.0, Url::CHANGE_FREQUENCY_WEEKLY],
            ['site.about', 0.8, Url::CHANGE_FREQUENCY_MONTHLY],
            ['site.terreiros.search', 0.9, Url::CHANGE_FREQUENCY_WEEKLY],
            ['site.terreiros.create', 0.7, Url::CHANGE_FREQUENCY_MONTHLY],
            ['site.partners-entities', 0.8, Url::CHANGE_FREQUENCY_MONTHLY],
            ['site.trans-people', 0.8, Url::CHANGE_FREQUENCY_MONTHLY],
            ['site.blog.posts', 0.8, Url::CHANGE_FREQUENCY_DAILY],
            ['site.links.external', 0.6, Url::CHANGE_FREQUENCY_MONTHLY],
            ['site.guidelines', 0.4, Url::CHANGE_FREQUENCY_YEARLY],
            ['site.privacy', 0.3, Url::CHANGE_FREQUENCY_YEARLY],
        ];

        foreach ($staticRoutes as [$name, $priority, $frequency]) {
            $sitemap->add(
                Url::create(route($name))
                    ->setPriority($priority)
                    ->setChangeFrequency($frequency),
            );
        }

        // Posts publicados.
        Post::query()
            ->published()
            ->get(['slug', 'updated_at'])
            ->each(function (Post $post) use ($sitemap): void {
                $sitemap->add(
                    Url::create(route('site.blog.show', $post))
                        ->setLastModificationDate($post->updated_at)
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY),
                );
            });

        // Páginas estáticas ativas.
        StaticPage::query()
            ->where('status', '=', Status::ACTIVE)
            ->get(['slug', 'updated_at'])
            ->each(function (StaticPage $page) use ($sitemap): void {
                $sitemap->add(
                    Url::create(route('site.static.page', $page))
                        ->setLastModificationDate($page->updated_at)
                        ->setPriority(0.5)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY),
                );
            });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap gerado em ' . public_path('sitemap.xml') . ' (' . count($sitemap->getTags()) . ' URLs).');

        return self::SUCCESS;
    }
}
