<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Suggestion;
use App\Models\Terreiro;
use App\Models\TerreiroQuestion;
use App\Models\TypePeople;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<TerreiroQuestion> */
class TerreiroQuestionFactory extends Factory
{
    protected $model = TerreiroQuestion::class;

    public function definition(): array
    {
        $yesNo = fn (): string => fake()->randomElement(['sim', 'nao']);

        return [
            'terreiro_id' => Terreiro::factory(),
            'type_people_id' => TypePeople::query()->inRandomOrder()->value('id'),
            'number_of_children_of_saint' => (string) fake()->numberBetween(0, 60),
            'number_of_children_of_saint_trans' => (string) fake()->numberBetween(0, 20),
            'trans_men_and_women' => $yesNo(),
            'name_gender' => $yesNo(),
            'fully_welcomes' => $yesNo(),
            'respect_for_trans_people' => $yesNo(),
            'suffered_aggregation' => $yesNo(),
            'inclusion_of_the_name_of_the_land' => $yesNo(),
            'suggestion_id' => Suggestion::query()->inRandomOrder()->value('id'),
            'suggestion_text' => fake()->optional()->sentence(),
        ];
    }
}
