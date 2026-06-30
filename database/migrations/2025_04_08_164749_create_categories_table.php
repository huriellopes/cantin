<?php

declare(strict_types=1);

use App\Enum\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id()->index();
            $table->string('name')->index();
            $table->string('slug')
                ->unique()
                ->index();
            $table->smallInteger('status')
                ->default(Status::ACTIVE);
            $table->timestamps();

            $table->index('created_at');
        });

        if (app()->isProduction()) {
            DB::table('categories')
                ->insert([
                    [
                        'name' => 'Terreiros',
                        'slug' => 'terreiros',
                        'created_at' => Date::now(),
                        'updated_at' => Date::now(),
                    ],
                    [
                        'name' => 'Candomblé',
                        'slug' => 'candomble',
                        'created_at' => Date::now(),
                        'updated_at' => Date::now(),
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
