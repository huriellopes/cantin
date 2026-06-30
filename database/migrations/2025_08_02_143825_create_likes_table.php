<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')
                ->index()
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('comment_id')
                ->index()
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->ipAddress()->index();
            $table->foreignId('post_id')
                ->index()
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'comment_id', 'post_id'], 'user_comment_post_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
