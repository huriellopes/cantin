<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')
            ->insert([
                [
                    'name' => 'Terreiros',
                    'slug' => 'terreiros',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Candomblé',
                    'slug' => 'candomble',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
            ]);
    }
}
