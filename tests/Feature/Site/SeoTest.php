<?php

declare(strict_types=1);

it('blocks all crawlers in the robots.txt outside production', function (): void {
    // No ambiente de testes (não-produção) o robots deve bloquear tudo e não
    // expor o sitemap, evitando indexação de conteúdo de homologação.
    $response = $this->get('/robots.txt');

    $response->assertOk()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('Disallow: /', false);

    expect($response->getContent())->not->toContain('Sitemap:');
});

it('allows indexing and points to the sitemap in production', function (): void {
    app()->detectEnvironment(fn (): string => 'production');

    $this->get('/robots.txt')
        ->assertOk()
        ->assertSee('Disallow: /admin', false)
        ->assertSee('Sitemap: ' . url('/sitemap.xml'), false);
});

it('generates a sitemap file containing the public routes', function (): void {
    @unlink(public_path('sitemap.xml'));

    $this->artisan('sitemap:generate')->assertSuccessful();

    expect(public_path('sitemap.xml'))->toBeFile();

    $xml = (string) file_get_contents(public_path('sitemap.xml'));

    expect($xml)
        ->toContain(route('site.home'))
        ->toContain(route('site.blog.posts'))
        ->toContain(route('site.terreiros.search'));
});

it('renders SEO meta tags and the organization JSON-LD on the home page', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertSee('<meta property="og:title"', false)
        ->assertSee('<meta property="og:url"', false)
        ->assertSee('application/ld+json', false)
        ->assertSee('"@type":"Organization"', false);
});
