<?php

namespace Database\Seeders;

use App\Archicture\Entities\StatusMenusSites\Enum\StatusMenuSiteEnum;
use App\Enums\Status;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusSitesSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menus_sites')->insert([
            [
                'name' => 'home',
                'description' => 'Home Page',
                'route' => 'home',
                'status' => Status::ACTIVE->value,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'sobre',
                'description' => 'About Page',
                'route' => 'about',
                'status' => Status::ACTIVE->value,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'entidades parceiras',
                'description' => 'Partners Entities Page',
                'route' => 'partners.entities',
                'status' => Status::ACTIVE->value,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'pessoas trans',
                'description' => 'Peoples Trans Page',
                'route' => 'people.trans',
                'status' => Status::ACTIVE->value,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'cadastro de terreiros',
                'description' => 'Create Terreiros Page',
                'route' => 'create.terreiros',
                'status' => Status::INACTIVE->value,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'terreiros',
                'description' => 'Search Terreiros Page',
                'route' => 'search.terreiros',
                'status' => Status::INACTIVE->value,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'blog',
                'description' => 'Blog Page',
                'route' => 'blog',
                'status' => Status::INACTIVE->value,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'contato',
                'description' => 'Contact Page',
                'route' => 'contact',
                'status' => Status::ACTIVE->value,
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
        ]);
    }
}
