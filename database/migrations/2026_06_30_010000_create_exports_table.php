<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        // Remove a tabela 'exports' legada do Filament (removido do projeto),
        // que tinha outro schema. Sem dados úteis a preservar.
        Schema::dropIfExists('exports');

        Schema::create('exports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('disk')->default('local');
            $table->string('path');
            $table->string('status', 20)->default('processing'); // processing | ready | failed
            $table->boolean('notified')->default(false);
            $table->timestamp('downloaded_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exports');
    }
};
