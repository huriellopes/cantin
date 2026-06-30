<?php

declare(strict_types=1);

it('serves public pages', function (string $path) {
    $this->get($path)->assertOk();
})->with([
    '/',
    '/sobre',
    '/blog',
    '/terreiros',
    '/entidades-parceiras',
    '/pessoas-trans',
    '/links',
    '/terreiros/cadastro',
    '/login',
    '/politica-de-privacidade',
    '/diretrizes',
]);

it('serves every admin page for a super-admin', function (string $route) {
    $this->actingAs(userWithRole('super-admin'))
        ->get(route($route))
        ->assertOk();
})->with([
    'admin.dashboard',
    'admin.profile',
    'admin.terreiros.index',
    'admin.nations.index',
    'admin.type-terreiros.index',
    'admin.type-peoples.index',
    'admin.trans-peoples.index',
    'admin.partner-entities.index',
    'admin.posts.index',
    'admin.categories.index',
    'admin.comments.index',
    'admin.pages.index',
    'admin.static-pages.index',
    'admin.type-external-links.index',
    'admin.external-links.index',
    'admin.users.index',
    'admin.deleted-models.index',
]);

it('shows breadcrumbs on admin pages', function () {
    $this->actingAs(userWithRole('super-admin'))
        ->get(route('admin.terreiros.index'))
        ->assertOk()
        ->assertSee('Painel')
        ->assertSee('Terreiros');
});

it('redirects guests away from the admin', function () {
    $this->get('/admin')->assertRedirect(route('site.auth.login'));
});
