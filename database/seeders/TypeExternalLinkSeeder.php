<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeExternalLinkSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('type_external_links')->insert([
            [
                'name' => 'Livros',
                'slug' => 'livros',
            ],
            [
                'name' => 'Apostilas',
                'slug' => 'apostilas',
            ],
            [
                'name' => 'E-books',
                'slug' => 'e-books',
            ],
        ]);
    }
}
