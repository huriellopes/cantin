<?php

namespace Database\Seeders;

use App\Enum\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enum\Role as RoleEnum;

class UserSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(100)->create();

        User::query()->create([
            'name' => 'Huriel Lopes',
            'slug' => 'huriellopes',
            'email' => 'huriellopes1996@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('Hpr#899629'),
            'role_id' => RoleEnum::SUPER,
            'status' => Status::ACTIVE,
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        User::query()->create([
            'name' => 'Jorge Alan Baloni',
            'slug' => 'alanbaloni',
            'email' => 'seggvg@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('secret123'),
            'role_id' => RoleEnum::ADMIN,
            'status' => Status::ACTIVE,
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
