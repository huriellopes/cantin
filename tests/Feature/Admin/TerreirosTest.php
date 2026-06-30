<?php

declare(strict_types=1);

use App\Livewire\Admin\Terreiros\Index;
use App\Models\City;
use App\Models\NationsTerreiro;
use App\Models\State;
use App\Models\Suggestion;
use App\Models\Terreiro;
use App\Models\TypePeople;
use Livewire\Livewire;

function terreiroRefs(): array
{
    $state = State::query()->create(['name' => 'São Paulo', 'abbr' => 'SP', 'slug' => 'sp']);
    $city = City::query()->create(['name' => 'São Paulo', 'state_id' => $state->id, 'slug' => 'sao-paulo']);
    $nation = NationsTerreiro::query()->create(['name' => 'Ketu', 'slug' => 'ketu']);
    $type = TypePeople::query()->create(['name' => 'Mulher trans', 'slug' => 'mulher-trans', 'description' => '-']);
    $suggestion = Suggestion::query()->create(['name' => 'Sugestão', 'slug' => 'sugestao', 'description' => '-']);

    return ['state' => $state, 'city' => $city, 'nation' => $nation, 'type' => $type, 'suggestion' => $suggestion];
}

it('lets an admin open the terreiros page', function (): void {
    $this->actingAs(userWithRole('admin'))
        ->get('/admin/terreiros')
        ->assertOk()
        ->assertSeeLivewire(Index::class);
});

it('validates required fields', function (): void {
    Livewire::actingAs(userWithRole('admin'))->test(Index::class)
        ->call('create')
        ->call('save')
        ->assertHasErrors(['name', 'phone', 'nation_terreiro_id', 'state_id', 'city_id', 'type_people_id']);
});

it('creates a terreiro with address and questionnaire', function (): void {
    $refs = terreiroRefs();

    Livewire::actingAs(userWithRole('admin'))->test(Index::class)
        ->call('create')
        ->set('name', 'Ilê Axé')
        ->set('phone', '(11) 9 9999-9999')
        ->set('nation_terreiro_id', $refs['nation']->id)
        ->set('leadership_orunko', 'Mãe Ana')
        ->set('color_of_leadership', 'preta')
        ->set('zipcode', '01001-000')
        ->set('address', 'Praça da Sé')
        ->set('neighborhood', 'Sé')
        ->set('state_id', $refs['state']->id)
        ->set('city_id', $refs['city']->id)
        ->set('type_people_id', $refs['type']->id)
        ->set('number_of_children_of_saint', '10')
        ->set('number_of_children_of_saint_trans', '3')
        ->set('trans_men_and_women', 'sim')
        ->set('name_gender', 'sim')
        ->set('fully_welcomes', 'sim')
        ->set('respect_for_trans_people', 'sim')
        ->set('suffered_aggregation', 'nao')
        ->set('inclusion_of_the_name_of_the_land', 'sim')
        ->set('suggestion_id', $refs['suggestion']->id)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    $terreiro = Terreiro::query()->with(['address', 'question'])->first();
    expect($terreiro)->not->toBeNull()
        ->and($terreiro->name)->toBe('Ilê Axé')
        ->and($terreiro->phone)->toBe('11999999999')
        ->and($terreiro->address)->not->toBeNull()
        ->and($terreiro->address->neighborhood)->toBe('Sé')
        ->and($terreiro->question)->not->toBeNull()
        ->and($terreiro->question->type_people_id)->toBe($refs['type']->id);
});
