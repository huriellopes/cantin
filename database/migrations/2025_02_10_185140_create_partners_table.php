<?php

use App\Enum\Status as StatusEnum;
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
        Schema::create('partners', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name')->index();
            $table->string('email')
                ->index()
                ->nullable();
            $table->string('phone')->nullable();
            $table->string('path_image')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->smallInteger('status')->default(StatusEnum::ACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
