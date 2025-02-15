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
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->date('fundationed_at');
            $table->foreignId('nation_terreiro_id')
                ->constrained('nations_terreiros');
            $table->foreignId('address_id')->constrained();
            $table->string('leadership_orunko'); // Orunkó da liderança
            $table->string("color_of_leadership"); // Qual a cor da pele da liderança do terreiro?
            $table->timestamps();
            $table->softDeletes();
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
