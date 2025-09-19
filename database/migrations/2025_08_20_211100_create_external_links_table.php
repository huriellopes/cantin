<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('external_links', function (Blueprint $table) {
            $table->id();
            $table->string('title')
                ->index();
            $table->string('slug')
                ->unique()
                ->index();
            $table->string('description');
            $table->string('url')
                ->index();
            $table->smallInteger('status')
                ->default(\App\Enum\Status::INACTIVE);
            $table->foreignId('user_id')
                ->index()
                ->constrained('users');
            $table->foreignId('type_external_link_id')
                ->index()
                ->constrained('type_external_links');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_links');
    }
};
