<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('type_external_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->smallInteger('status')->default(\App\Enum\Status::ACTIVE);
            $table->timestamps();
        });

        if (app()->isProduction()) {
            DB::table('type_external_links')
                ->insert([
                    [
                        'name' => 'Livros',
                        'slug' => 'livros',
                    ],
                    [
                        'name' => 'Apostilas',
                        'slug' => 'apostilas',
                    ],
                    [
                        'name' => 'E-books',
                        'slug' => 'e-books',
                    ]
                ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('type_external_links');
    }
};
