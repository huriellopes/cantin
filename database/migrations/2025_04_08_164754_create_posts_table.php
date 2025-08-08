<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\StatusPost;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slug')
                ->unique()
                ->index();
            $table->longText('content');
            $table->string('main_image')
                ->nullable();
            $table->timestamp('published_at');
            $table->smallInteger('status')
                ->index()
                ->default(StatusPost::PENDING);
            $table->integer('views')->default(0);
            $table->foreignId('user_id')
                ->index()
                ->constrained();
            $table->foreignId('category_id')
                ->nullable()
                ->index()
                ->constrained();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
