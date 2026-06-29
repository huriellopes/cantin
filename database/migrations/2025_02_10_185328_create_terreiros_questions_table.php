<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('terreiros_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terreiro_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('type_people_id'); // Sexo da liderança
            $table->integer('number_of_children_of_saint'); // Quantidade de filhos de santo
            $table->integer('number_of_children_of_saint_trans'); // Quantidade de filhos de santo são trans
            $table->string('trans_men_and_women'); // As pessoas trans do terreiro usam roupas segundo o gênero que se identificam? Ex. mulheres trans usam saia? Homens trans usam calça?
            $table->string('name_gender'); // As pessoas trans do terreiro são chamadas pelo nome e gênero que desejam?
            $table->string('fully_welcomes'); // A família espiritual acolhe integralmente as pessoas trans do terreiro ou a liderança ainda precisa mediar as relações?
            $table->string('respect_for_trans_people'); // O terreiro fez alguma ação de conscientização da necessidade de acolhimento respeitoso de pessoas trans em suas dependências?
            $table->string('suffered_aggregation'); // A liderança e as pessoas trans do terreiro foram hostilizadas quando os demais terreiros souberam que essas pessoas são respeitadas na casa?
            $table->string('inclusion_of_the_name_of_the_land'); // Podemos incluir o nome e o contato do seu terreiro na lista de indicações de casas trans-inclusivas para ORÍentar
            $table->foreignId('suggestion_id')
                ->index()
                ->nullable()
                ->constrained();
            $table->text('suggestion_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terreiros_questions');
    }
};
