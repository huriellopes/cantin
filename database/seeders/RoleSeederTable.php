<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
        ]);

        Role::query()->create([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        Role::query()->create([
            'name' => 'User',
            'slug' => 'user',
        ]);
    }
}
