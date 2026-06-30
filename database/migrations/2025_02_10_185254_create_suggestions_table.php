<?php

declare(strict_types=1);

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
        Schema::create('suggestions', function (Blueprint $table): void {
            $table->id();
            $table->string('name')
                ->index();
            $table->string('slug')
                ->unique()
                ->index();
            $table->text('description')
                ->nullable();
            $table->timestamps();
        });

        if (app()->isProduction()) {
            DB::table('suggestions')
                ->insert([
                    [
                        'name' => 'Criticas',
                        'slug' => 'criticas',
                        'description' => 'Criticas construtivas e sugestões de melhorias',
                        'created_at' => Date::now(),
                        'updated_at' => Date::now(),
                    ],
                    [
                        'name' => 'Dúvidas',
                        'slug' => 'duvidas',
                        'description' => null,
                        'created_at' => Date::now(),
                        'updated_at' => Date::now(),
                    ],
                    [
                        'name' => 'Indicações',
                        'slug' => 'indicacoes',
                        'description' => null,
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
        Schema::dropIfExists('suggestions');
    }
};
