<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Estados e cidades vêm da API oficial do IBGE (fonte canônica).
        // Pulado em testes para não depender de rede.
        if (!app()->runningUnitTests()) {
            Artisan::call('localidades:sync');
        }

        $this->call([
            RoleSeederTable::class,
            UserSeederTable::class,
            TypePeopleSeederTable::class,
            TypeTerreiroSeederTable::class,
            NationsTerreirosSeederTable::class,
            AddressSeeder::class,
            SuggestionSeederTable::class,
            CommonQuestionsSeederTable::class,
            CategorySeeder::class,
            PostSeeder::class,
            TypeExternalLinkSeeder::class,
            PartnerEntitySeeder::class,
            TransPeopleSeeder::class,
            ExternalLinkSeeder::class,
            PageSeeder::class,
        ]);
    }
}
