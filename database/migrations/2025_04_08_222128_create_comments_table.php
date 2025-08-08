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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null')
                ->index();
            $table->ipAddress();
            $table->string('name')
                ->nullable()
                ->index();
            $table->string('email')
                ->nullable()
                ->unique()
                ->index();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')
                ->nullable()->constrained('comments')->onDelete('cascade');
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
