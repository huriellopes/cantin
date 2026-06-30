<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class TypePeopleSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_peoples')
            ->insert([
                [
                    'name' => 'Travesti',
                    'slug' => 'travesti',
                    'description' => '',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Pessoa Trans',
                    'slug' => 'pessoa-trans',
                    'description' => '',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Não Binária',
                    'slug' => 'nao-binaria',
                    'description' => '',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Pessoa Cis',
                    'slug' => 'pessoa-cis',
                    'description' => 'Pessoa que não é trans',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Não se aplica',
                    'slug' => 'nao-se-aplica',
                    'description' => 'Não se aplica o sexo da pessoa',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
            ]);
    }
}
