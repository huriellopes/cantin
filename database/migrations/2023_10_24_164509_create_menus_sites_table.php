<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Archicture\Entities\StatusMenusSites\Enum\StatusMenuSiteEnum;

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
            $table->integer('status_menus_sites_id')->default(StatusMenuSiteEnum::ACTIVE->value);
            $table->foreign('status_menus_sites_id')
                ->references('id')
                ->on('status_menus_sites');
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
