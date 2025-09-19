<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('type_terreiros', function (Blueprint $table) {
            $table->id();
            $table->string("name")
                ->index();
            $table->string('slug')
                ->unique()
                ->index();
            $table->string('description')
                ->nullable();
            $table->timestamps();
        });

        if (app()->isProduction()) {
            DB::table('type_terreiros')->insert([
                [
                    'name' => 'Trans-inclusivos',
                    'slug' => 'trans-inclusivos',
                    'description' => 'Terreiro que é trans-inclusivo',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => 'Não se aplica',
                    'slug' => 'nao-se-aplica',
                    'description' => null,
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
        Schema::dropIfExists('type_terreiros');
    }
};
