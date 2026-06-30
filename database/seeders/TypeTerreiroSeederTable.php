<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class TypeTerreiroSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_terreiros')->insert([
            [
                'name' => 'Trans-inclusivos',
                'slug' => 'trans-inclusivos',
                'description' => 'Terreiro que é trans-inclusivo',
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'name' => 'Não se aplica',
                'slug' => 'nao-se-aplica',
                'description' => null,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
        ]);
    }
}
