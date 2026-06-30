<?php

declare(strict_types=1);

use App\Enum\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * As perguntas frequentes passaram a vir dos arquivos de tradução
     * (lang/{locale}/faq.php); a tabela common_questions ficou órfã e é removida.
     */
    public function up(): void
    {
        Schema::dropIfExists('common_questions');
    }

    public function down(): void
    {
        Schema::create('common_questions', function (Blueprint $table): void {
            $table->id();
            $table->string('question');
            $table->string('answer');
            $table->smallInteger('status')
                ->index()
                ->default(Status::ACTIVE);
            $table->timestamps();

            $table->index('created_at');
        });
    }
};
