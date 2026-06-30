<?php

declare(strict_types=1);

use App\Enum\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name')
                ->index();
            $table->string('slug')
                ->unique()
                ->index();
            $table->longText('content');
            $table->smallInteger('status')
                ->index()
                ->default(Status::ACTIVE);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
