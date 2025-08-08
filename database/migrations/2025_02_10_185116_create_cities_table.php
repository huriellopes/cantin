<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name')->index();
            $table->foreignId('state_id')
                ->constrained();
            $table->timestamps();
        });

        if (app()->isProduction()) {
            DB::unprepared(file_get_contents(__DIR__ . '/sql/CitySeeder.sql'));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
