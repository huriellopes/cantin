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
        ->assertHasErrors(['form.name', 'form.phone', 'form.nation_terreiro_id', 'form.state_id', 'form.city_id', 'form.type_people_id']);
});

it('creates a terreiro with address and questionnaire', function (): void {
    $refs = terreiroRefs();

    Livewire::actingAs(userWithRole('admin'))->test(Index::class)
        ->call('create')
        ->set('form.name', 'Ilê Axé')
        ->set('form.phone', '(11) 9 9999-9999')
        ->set('form.nation_terreiro_id', $refs['nation']->id)
        ->set('form.leadership_orunko', 'Mãe Ana')
        ->set('form.color_of_leadership', 'preta')
        ->set('form.zipcode', '01001-000')
        ->set('form.address', 'Praça da Sé')
        ->set('form.neighborhood', 'Sé')
        ->set('form.state_id', $refs['state']->id)
        ->set('form.city_id', $refs['city']->id)
        ->set('form.type_people_id', $refs['type']->id)
        ->set('form.number_of_children_of_saint', '10')
        ->set('form.number_of_children_of_saint_trans', '3')
        ->set('form.trans_men_and_women', 'sim')
        ->set('form.name_gender', 'sim')
        ->set('form.fully_welcomes', 'sim')
        ->set('form.respect_for_trans_people', 'sim')
        ->set('form.suffered_aggregation', 'nao')
        ->set('form.inclusion_of_the_name_of_the_land', 'sim')
        ->set('form.suggestion_id', $refs['suggestion']->id)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    $terreiro = Terreiro::query()->with(['address', 'question'])->first();
    expect($terreiro)->not->toBeNull()
        ->and($terreiro->name)->toBe('Ilê Axé')
        // Gravado sem máscara no banco; exibido com máscara pelo accessor.
        ->and($terreiro->getRawOriginal('phone'))->toBe('11999999999')
        ->and($terreiro->phone)->toBe('(11) 9 9999-9999')
        ->and($terreiro->address)->not->toBeNull()
        ->and($terreiro->address->neighborhood)->toBe('Sé')
        ->and($terreiro->question)->not->toBeNull()
        ->and($terreiro->question->type_people_id)->toBe($refs['type']->id);
});
