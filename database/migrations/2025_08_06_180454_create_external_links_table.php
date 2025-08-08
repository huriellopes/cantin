<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('external_links', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('description');
            $table->string('url')
                ->index();
            $table->smallInteger('status')
                ->default(0);
            $table->foreignId('user_id')
                ->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_links');
    }
};
