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
        Schema::create('menus_sites', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name')->index();
            $table->string('description')->nullable();
            $table->string('route')->nullable();
            $table->smallInteger('status')->default(\App\Enum\StatusEnum::ACTIVE);
            $table->foreignId('user_id')
                ->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus_sites');
    }
};
