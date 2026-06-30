<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('type_peoples', function (Blueprint $table) {
            $table->id();
            $table->string('name')
                ->index();
            $table->string('slug')
                ->unique()
                ->index();
            $table->string('description')
                ->nullable();
            $table->timestamps();
        });

        if (app()->isProduction()) {
            DB::table('type_peoples')
                ->insert([
                    [
                        'name' => 'Travesti',
                        'slug' => 'travesti',
                        'description' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                    [
                        'name' => 'Pessoa Trans',
                        'slug' => 'pessoa-trans',
                        'description' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                    [
                        'name' => 'Não Binária',
                        'slug' => 'nao-binaria',
                        'description' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                    [
                        'name' => 'Pessoa Cis',
                        'slug' => 'pessoa-cis',
                        'description' => 'Pessoa que não é trans',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                    [
                        'name' => 'Não se aplica',
                        'slug' => 'nao-se-aplica',
                        'description' => 'Não se aplica o sexo da pessoa',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_peoples');
    }
};
