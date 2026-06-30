<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as Trail;

// Raiz do painel
Breadcrumbs::for('admin.dashboard', function (Trail $trail): void {
    $trail->push(__('admin.nav.dashboard'), route('admin.dashboard'));
});

// Páginas de listagem do admin (todas filhas do Painel) — rótulos reutilizam admin.nav.*
$adminPages = [
    'admin.terreiros.index' => 'terreiros',
    'admin.nations.index' => 'nations',
    'admin.type-terreiros.index' => 'type_terreiros',
    'admin.type-peoples.index' => 'genders',
    'admin.trans-peoples.index' => 'trans_people',
    'admin.partner-entities.index' => 'partners',
    'admin.posts.index' => 'posts',
    'admin.categories.index' => 'categories',
    'admin.comments.index' => 'comments',
    'admin.pages.index' => 'pages',
    'admin.static-pages.index' => 'static_pages',
    'admin.type-external-links.index' => 'link_types',
    'admin.external-links.index' => 'external_links',
    'admin.users.index' => 'users',
    'admin.deleted-models.index' => 'deleted_models',
    'admin.impersonation-logs.index' => 'impersonation_logs',
];

foreach ($adminPages as $name => $navKey) {
    Breadcrumbs::for($name, function (Trail $trail) use ($name, $navKey): void {
        $trail->parent('admin.dashboard');
        $trail->push(__('admin.nav.' . $navKey), route($name));
    });
}
