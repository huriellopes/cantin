<?php

use App\Enum\Status;
use App\Livewire\Admin\TransPeoples\Index;
use App\Models\City;
use App\Models\PartnerEntity;
use App\Models\State;
use App\Models\TransPeople;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

function addressPair(): array
{
    $state = State::query()->create(['name' => 'São Paulo', 'abbr' => 'SP', 'slug' => 'sp-'.uniqid()]);
    $city = City::query()->create(['name' => 'São Paulo', 'state_id' => $state->id, 'slug' => 'sp-'.uniqid()]);

    return [$state, $city];
}

it('creates a trans person with address', function () {
    [$state, $city] = addressPair();

    Livewire::actingAs(userWithRole('admin'))
        ->test(Index::class)
        ->call('create')
        ->set('name', 'Alex')
        ->set('email', 'alex@example.com')
        ->set('phone', '(11) 9 8888-7777')
        ->set('zipcode', '01001-000')
        ->set('address', 'Praça da Sé')
        ->set('neighborhood', 'Sé')
        ->set('state_id', $state->id)
        ->set('city_id', $city->id)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    $person = TransPeople::query()->with('address')->first();
    expect($person->name)->toBe('Alex')
        ->and($person->status)->toBe(Status::ACTIVE)
        ->and($person->address->neighborhood)->toBe('Sé');
});

it('creates a partner entity with image and author', function () {
    Storage::fake('public');
    [$state, $city] = addressPair();
    $admin = userWithRole('admin');

    Livewire::actingAs($admin)
        ->test(App\Livewire\Admin\PartnerEntities\Index::class)
        ->call('create')
        ->set('name', 'ONG Acolher')
        ->set('email', 'contato@acolher.org')
        ->set('phone', '(11) 3333-4444')
        ->set('activity_carried_out', 'Acolhimento e apoio jurídico')
        ->set('image', UploadedFile::fake()->image('logo.jpg'))
        ->set('zipcode', '01001-000')
        ->set('address', 'Praça da Sé')
        ->set('neighborhood', 'Sé')
        ->set('state_id', $state->id)
        ->set('city_id', $city->id)
        ->call('save')
        ->assertHasNoErrors();

    $entity = PartnerEntity::query()->first();
    expect($entity->name)->toBe('ONG Acolher')
        ->and($entity->user_id)->toBe($admin->id)
        ->and($entity->path_image)->not->toBeNull();

    Storage::disk('public')->assertExists($entity->path_image);
});

it('requires an image when creating a partner entity', function () {
    Livewire::actingAs(userWithRole('admin'))
        ->test(App\Livewire\Admin\PartnerEntities\Index::class)
        ->call('create')
        ->set('name', 'X')
        ->set('email', 'x@x.com')
        ->set('phone', '11999999999')
        ->set('activity_carried_out', 'y')
        ->set('zipcode', '01001-000')
        ->set('address', 'rua')
        ->set('neighborhood', 'b')
        ->call('save')
        ->assertHasErrors(['image', 'state_id', 'city_id']);
});
