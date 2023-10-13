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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('zipcode'); // Cep
            $table->string('address'); // Endereço
            $table->string('complement'); // Complemento
            $table->string('number', 10); // Número
            $table->string('neighborhood'); // Bairro
            $table->foreignId('state_id')->constrained(); // Estado
            $table->foreignId('city_id')->constrained(); // Cidade
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
