<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\NationsTerreiro;
use App\Models\Terreiro;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Terreiro> */
class TerreiroFactory extends Factory
{
    protected $model = Terreiro::class;

    public function definition(): array
    {
        return [
            'name' => 'Terreiro ' . fake()->unique()->words(2, true),
            // Telefone só com dígitos (11), como é gravado pelo admin.
            'phone' => fake()->numerify('###########'),
            'nation_terreiro_id' => NationsTerreiro::query()->inRandomOrder()->value('id'),
            'leadership_orunko' => fake()->firstName(),
            'color_of_leadership' => fake()->randomElement(['branca', 'preta', 'azul', 'vermelha', 'amarela', 'verde']),
            'address_id' => Address::factory(),
        ];
    }
}
