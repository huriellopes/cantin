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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('zipcode')
                ->index(); // Cep
            $table->string('address'); // Endereço
            $table->string('complement')
                ->nullable(); // Complemento
            $table->string('neighborhood'); // Bairro
            $table->foreignId('state_id')
                ->index()
                ->constrained(); // Estado
            $table->foreignId('city_id')
                ->index()
                ->constrained(); // Cidade
            $table->decimal('latitude', 10, 8)
                ->nullable();
            $table->decimal('longitude', 11, 8)
                ->nullable();
            $table->timestamps();
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
