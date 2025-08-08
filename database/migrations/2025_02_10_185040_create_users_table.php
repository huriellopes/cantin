<?php

use App\Enum\Status as StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\Role as RoleEnum;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()
                ->index();
            $table->string('name')
                ->index();
            $table->string('slug')
                ->unique()
                ->index();
            $table->string('email')
                ->index();
            $table->timestamp('email_verified_at')
                ->nullable();
            $table->string('password');
            $table->foreignId('role_id')
                ->index()
                ->constrained();
            $table->smallInteger('status')
                ->index()
                ->default(StatusEnum::ACTIVE);
            $table->rememberToken();
            $table->timestamps();
        });

        if (app()->isProduction()) {
            DB::table('users')->insert([
                [
                    'name' => 'Huriel Lopes',
                    'email' => 'huriellopes1996@gmail.com',
                    'slug' => 'huriellopes',
                    'email_verified_at' => Carbon::now(),
                    'password' => bcrypt('Hpr#89962910'),
                    'role_id' => RoleEnum::SUPER,
                    'status' => StatusEnum::ACTIVE,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => 'Jorge Alan Baloni',
                    'slug' => 'alanbaloni',
                    'email' => 'alanbaloni@gmail.com',
                    'email_verified_at' => Carbon::now(),
                    'password' => bcrypt('secret123'),
                    'role_id' => RoleEnum::ADMIN,
                    'status' => StatusEnum::ACTIVE,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
