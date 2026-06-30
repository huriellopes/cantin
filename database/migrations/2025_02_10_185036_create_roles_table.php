<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')
                ->index();
            $table->string('slug')
                ->unique()
                ->index();
            $table->timestamps();
        });

        if (app()->isProduction()) {
            DB::table('roles')->insert([
                [
                    'name' => 'Super Admin',
                    'slug' => 'super-admin',
                ],
                [
                    'name' => 'Admin',
                    'slug' => 'admin',
                ],
                [
                    'name' => 'User',
                    'slug' => 'user',
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
