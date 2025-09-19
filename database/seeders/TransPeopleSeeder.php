<?php

namespace Database\Seeders;

use App\Models\TransPeople;
use Illuminate\Database\Seeder;

class TransPeopleSeeder extends Seeder
{
    public function run(): void
    {
        TransPeople::factory()
            ->count(20)
            ->create();
    }
}
