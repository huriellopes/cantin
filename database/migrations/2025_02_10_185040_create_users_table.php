<?php

declare(strict_types=1);

use App\Enum\Status as StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')
                ->index();
            $table->string('slug')
                ->unique()
                ->index();
            $table->string('email')
                ->unique()
                ->index();
            $table->timestamp('email_verified_at')
                ->nullable();
            $table->string('password');
            $table->foreignId('role_id')
                ->index()
                ->constrained();
            $table->smallInteger('status')
                ->index()
                ->default(StatusEnum::ACTIVE);
            $table->rememberToken();
            $table->timestamps();
        });

        // A criação de usuários administradores foi movida para o UserSeederTable,
        // com credenciais lidas de variáveis de ambiente (sem segredos no código).
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
