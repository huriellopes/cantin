<?php

declare(strict_types=1);

use App\Enum\Status;
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
        Schema::create('trans_peoples', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->index();
            $table->string('email')
                ->unique()
                ->index();
            $table->string('phone');
            $table->foreignId('address_id')
                ->index()
                ->constrained('addresses')
                ->onDelete('cascade');
            $table->smallInteger('status')
                ->default(Status::ACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_peoples');
    }
};
