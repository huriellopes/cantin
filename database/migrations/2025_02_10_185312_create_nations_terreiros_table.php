<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nations_terreiros', function (Blueprint $table): void {
            $table->id();
            $table->string('name')
                ->index();
            $table->string('slug')
                ->index();
            $table->timestamps();

            $table->index('created_at');
        });

        if (app()->isProduction()) {
            DB::table('nations_terreiros')->insert([
                [
                    'name' => 'Candomblé Ketu',
                    'slug' => 'candomble-ketu',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Candomblé Jeje',
                    'slug' => 'candomble-jeje',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Candomblé Nagô',
                    'slug' => 'candomble-nago',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Candomblé Angola',
                    'slug' => 'candomble-angola',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Umbanda',
                    'slug' => 'umbanda',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Tambor de Mina',
                    'slug' => 'tambor-de-mina',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Xangô',
                    'slug' => 'xango',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Batuque',
                    'slug' => 'batuque',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
                [
                    'name' => 'Outros',
                    'slug' => 'outros',
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nations_terreiros');
    }
};
