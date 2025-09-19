<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name')->index();
            $table->string('slug')
                ->unique()
                ->index();
            $table->smallInteger('status')
                ->default(\App\Enum\Status::ACTIVE);
            $table->timestamps();

            $table->index('created_at');
        });

        if (app()->isProduction()) {
            DB::table('categories')
                ->insert([
                    [
                        'name' => 'Terreiros',
                        'slug' => 'terreiros',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ],
                    [
                        'name' => 'Candomblé',
                        'slug' => 'candomble',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ],
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
