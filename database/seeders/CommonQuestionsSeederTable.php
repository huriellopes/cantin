<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enum\Status;
use App\Models\CommonQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class CommonQuestionsSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CommonQuestion::query()->create([
            'question' => 'O que é o CANTIn?',
            'answer' => 'O CANTIn é um cadastro nacional que mapeia terreiros trans-inclusivos, conectando pessoas trans a espaços religiosos que acolhem e respeitam sua identidade de gênero.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        CommonQuestion::query()->create([
            'question' => 'Qual é o objetivo do CANTIn?',
            'answer' => 'O objetivo do CANTIn é promover visibilidade para sacerdotes trans e práticas inclusivas, além de consolidar as religiões afro-brasileiras como espaços de acolhimento, resistência e transformação social.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        CommonQuestion::query()->create([
            'question' => 'Quem pode se cadastrar no CANTIn?',
            'answer' => ' Lideranças religiosas de terreiros trans-inclusivos, pessoas trans em busca de terreiros, e entidades parceiras que desejam apoiar a iniciativa.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        CommonQuestion::query()->create([
            'question' => 'Como faço para cadastrar meu terreiro no CANTIn?',
            'answer' => 'É necessário preencher as informações solicitadas na página oficial, detalhando as práticas inclusivas e ações realizadas em seu terreiro.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        CommonQuestion::query()->create([
            'question' => 'Como posso encontrar um terreiro trans-inclusivo na minha região?',
            'answer' => 'A página do CANTIn oferece uma ferramenta de busca que permite localizar terreiros cadastrados por região ou estado. <a href="' . route('site.terreiros.search') . '" wire:navigate title="lista de terreiros">Lista de Terreiros</a>.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        CommonQuestion::query()->create([
            'question' => 'O CANTIn é apoiado por alguma instituição governamental?',
            'answer' => 'Sim, o projeto conta com apoio governamental, refletindo o compromisso público com a justiça social e a valorização da diversidade.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        CommonQuestion::query()->create([
            'question' => 'O CANTIn é gratuito?',
            'answer' => 'Sim, tanto o registro de terreiros quanto a consulta de informações são gratuitos.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        CommonQuestion::query()->create([
            'question' => 'Quem idealizou o CANTIn?',
            'answer' => 'O projeto foi idealizado pelo Babalorixá Alan de Ogun (Ogundeje), inspirado por sua pesquisa de mestrado sobre transgeneridade e religião afro-brasileira, com a colaboração de Egbon Adeloya Ojubará.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        CommonQuestion::query()->create([
            'question' => 'Por que o CANTIn é importante?',
            'answer' => 'O cadastro é um símbolo de luta pela igualdade e pelo respeito à diversidade, fortalecendo a inclusão nas religiões afro-brasileiras e promovendo visibilidade para lideranças trans.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        CommonQuestion::query()->create([
            'question' => 'Como entidades parceiras podem participar do CANTIn?',
            'answer' => 'Entidades interessadas podem se cadastrar para apoiar o projeto, ajudando a ampliar seu alcance e impacto social.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        CommonQuestion::query()->create([
            'question' => 'Quais benefícios o CANTIn oferece às pessoas trans?',
            'answer' => 'Ele facilita a busca por terreiros inclusivos, garante acolhimento e respeito, e promove uma rede de apoio em diferentes regiões do Brasil.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);

        CommonQuestion::query()->create([
            'question' => 'Como o CANTIn contribui para a luta pela inclusão?',
            'answer' => 'O projeto reforça a importância das tradições afro-brasileiras como espaços de acolhimento, combatendo preconceitos e promovendo justiça social.',
            'status' => Status::ACTIVE,
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
        ]);
    }
}
