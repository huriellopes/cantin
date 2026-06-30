<?php

declare(strict_types=1);

use App\Livewire\Admin\TypePeoples\Index;
use App\Models\Role;
use App\Models\TypePeople;
use App\Models\User;
use Livewire\Livewire;

/**
 * Garante que a criação NÃO injeta `id => null` nos atributos do modelo.
 * Isso passava silenciosamente no SQLite (autoincrement ignora null), mas
 * estourava no PostgreSQL ("null value in column id violates not-null").
 */
it('does not set a null id when creating via the base resource (type_peoples)', function (): void {
    $captured = null;
    TypePeople::creating(function ($model) use (&$captured): false {
        $captured = $model->getAttributes();

        return false; // aborta o insert; só inspecionamos os atributos
    });

    Livewire::actingAs(userWithRole('super-admin'))
        ->test(Index::class)
        ->call('create')
        ->set('form.name', 'Teste')
        ->set('form.slug', 'teste')
        ->set('form.description', 'teste')
        ->call('save')
        ->assertHasNoErrors();

    expect($captured)->not->toBeNull()
        ->and($captured)->not->toHaveKey('id');
});

it('does not set a null id when creating a user', function (): void {
    Role::query()->firstOrCreate(['slug' => 'user'], ['name' => 'User']);

    $captured = null;
    User::creating(function ($model) use (&$captured): false {
        $captured = $model->getAttributes();

        return false;
    });

    Livewire::actingAs(userWithRole('super-admin'))
        ->test(App\Livewire\Admin\Users\Index::class)
        ->call('create')
        ->set('name', 'Fulano')
        ->set('email', 'fulano@example.com')
        ->set('role_id', Role::query()->where('slug', 'user')->value('id'))
        ->call('save')
        ->assertHasNoErrors();

    expect($captured)->not->toBeNull()
        ->and($captured)->not->toHaveKey('id');
});
