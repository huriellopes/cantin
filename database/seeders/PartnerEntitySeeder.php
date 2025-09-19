<?php

namespace Database\Seeders;

use App\Models\PartnerEntity;
use Illuminate\Database\Seeder;

class PartnerEntitySeeder extends Seeder
{
    public function run(): void
    {
        PartnerEntity::factory()
            ->count(20)
            ->create();
    }
}
