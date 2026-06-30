<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as Trail;

// Raiz do painel
Breadcrumbs::for('admin.dashboard', function (Trail $trail): void {
    $trail->push('Painel', route('admin.dashboard'));
});

// Páginas de listagem do admin (todas filhas do Painel)
$adminPages = [
    'admin.terreiros.index' => 'Terreiros',
    'admin.nations.index' => 'Nações',
    'admin.type-terreiros.index' => 'Tipos de Terreiro',
    'admin.type-peoples.index' => 'Gêneros',
    'admin.trans-peoples.index' => 'Pessoas Trans',
    'admin.partner-entities.index' => 'Entidades Parceiras',
    'admin.posts.index' => 'Posts',
    'admin.categories.index' => 'Categorias',
    'admin.comments.index' => 'Comentários',
    'admin.pages.index' => 'Páginas',
    'admin.static-pages.index' => 'Páginas Estáticas',
    'admin.type-external-links.index' => 'Tipos de Link',
    'admin.external-links.index' => 'Links Externos',
    'admin.users.index' => 'Usuários',
    'admin.deleted-models.index' => 'Modelos Excluídos',
];

foreach ($adminPages as $name => $label) {
    Breadcrumbs::for($name, function (Trail $trail) use ($name, $label): void {
        $trail->parent('admin.dashboard');
        $trail->push($label, route($name));
    });
}
