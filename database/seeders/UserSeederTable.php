<?php

namespace Database\Seeders;

use App\Enums\Status;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Huriel Lopes',
                'username' => 'huriellopes',
                'email' => 'huriellopes1996@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt('secret123'),
                'level_id' => 1,
                'status' => Status::ACTIVE,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Jorge Alan Baloni',
                'username' => 'alanbaloni',
                'email' => 'alanbaloni@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt('secret123'),
                'level_id' => 1,
                'status' => Status::ACTIVE,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
