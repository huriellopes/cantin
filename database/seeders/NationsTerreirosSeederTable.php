<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\NationsTerreiro;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NationsTerreirosSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NationsTerreiro::query()->create([
            'name' => 'Candomblé Ketu',
            'slug' => 'candomble-ketu',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        NationsTerreiro::query()->create([
            'name' => 'Candomblé Jeje',
            'slug' => 'candomble-jeje',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        NationsTerreiro::query()->create([
            'name' => 'Candomblé Nagô',
            'slug' => 'candomble-nago',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        NationsTerreiro::query()->create([
            'name' => 'Candomblé Angola',
            'slug' => 'candomble-angola',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        NationsTerreiro::query()->create([
            'name' => 'Umbanda',
            'slug' => 'umbanda',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        NationsTerreiro::query()->create([
            'name' => 'Tambor de Mina',
            'slug' => 'tambor-de-mina',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        NationsTerreiro::query()->create([
            'name' => 'Xangô',
            'slug' => 'xango',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        NationsTerreiro::query()->create([
            'name' => 'Batuque',
            'slug' => 'batuque',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        NationsTerreiro::query()->create([
            'name' => 'Outros',
            'slug' => 'outros',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
