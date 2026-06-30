<?php

declare(strict_types=1);

use App\Enum\Status;
use App\Livewire\Admin\ExternalLinks\Index;
use App\Models\ExternalLink;
use App\Models\TypeExternalLink;
use Livewire\Livewire;

function aLinkType(): TypeExternalLink
{
    return TypeExternalLink::query()->create(['name' => 'Apoio', 'slug' => 'apoio-' . uniqid(), 'status' => Status::ACTIVE]);
}

it('lets an admin open the external links page', function () {
    $this->actingAs(userWithRole('admin'))
        ->get('/admin/external-links')
        ->assertOk()
        ->assertSeeLivewire(Index::class);
});

it('creates an external link with the author set', function () {
    $admin = userWithRole('admin');
    $type = aLinkType();

    Livewire::actingAs($admin)->test(Index::class)
        ->call('create')
        ->set('title', 'Disque 100')
        ->set('type_external_link_id', $type->id)
        ->set('url', 'https://www.gov.br/mdh')
        ->set('description', 'Canal de denúncias')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    $link = ExternalLink::query()->where('title', 'Disque 100')->first();
    expect($link)->not->toBeNull()
        ->and($link->slug)->toBe('disque-100')
        ->and($link->user_id)->toBe($admin->id)
        ->and($link->status)->toBe(Status::ACTIVE);
});

it('validates external link fields', function () {
    Livewire::actingAs(userWithRole('admin'))->test(Index::class)
        ->call('create')
        ->set('title', '')
        ->set('type_external_link_id', null)
        ->set('url', 'not-a-url')
        ->set('description', '')
        ->call('save')
        ->assertHasErrors(['title', 'type_external_link_id', 'url', 'description']);
});

it('toggles an external link status', function () {
    $type = aLinkType();
    $link = ExternalLink::factory()->create(['type_external_link_id' => $type->id, 'status' => Status::ACTIVE]);

    Livewire::actingAs(userWithRole('admin'))->test(Index::class)
        ->call('toggleStatus', $link->id);

    expect($link->fresh()->status)->toBe(Status::INACTIVE);
});
