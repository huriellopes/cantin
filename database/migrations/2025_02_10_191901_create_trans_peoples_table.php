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
        Schema::create('trans_peoples', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name')->index();
            $table->string('email')
                ->unique()
                ->index();
            $table->string('phone');
            $table->foreignId('address_id')->constrained('addresses');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_peoples');
    }
};
