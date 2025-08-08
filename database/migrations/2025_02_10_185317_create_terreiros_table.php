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
        Schema::create('terreiros', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name')->index();
            $table->string('phone');
            $table->foreignId('nation_terreiro_id')
                ->constrained('nations_terreiros');
            $table->foreignId('address_id')->constrained();
            $table->string('leadership_orunko'); // Orukó da liderança
            $table->string("color_of_leadership"); // Qual a cor da pele da liderança do terreiro?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terreiros');
    }
};
