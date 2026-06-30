<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ExternalLink;
use Illuminate\Database\Seeder;

class ExternalLinkSeeder extends Seeder
{
    public function run(): void
    {
        ExternalLink::factory()->count(10)->create();
    }
}
