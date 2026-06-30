<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('states', function (Blueprint $table): void {
            $table->unsignedInteger('ibge_code')->nullable()->index()->after('abbr');
        });

        Schema::table('cities', function (Blueprint $table): void {
            $table->unsignedInteger('ibge_code')->nullable()->index()->after('state_id');
        });
    }

    public function down(): void
    {
        Schema::table('states', function (Blueprint $table): void {
            $table->dropColumn('ibge_code');
        });

        Schema::table('cities', function (Blueprint $table): void {
            $table->dropColumn('ibge_code');
        });
    }
};
