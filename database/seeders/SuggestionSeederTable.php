<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class SuggestionSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suggestions')->insert([
            [
                'name' => 'Criticas',
                'slug' => 'criticas',
                'description' => 'Criticas construtivas e sugestões de melhorias',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Dúvidas',
                'slug' => 'duvidas',
                'description' => null,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Indicações',
                'slug' => 'indicacoes',
                'description' => null,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
        ]);
    }
}
