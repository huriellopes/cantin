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
        Schema::create('partners_entities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('activity_carried_out'); // Atividade Desenvolvida
            $table->string('email')
                ->unique()
                ->index();
            $table->string('phone');
            $table->foreignId('address_id')
                ->constrained('addresses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners_entities');
    }
};
