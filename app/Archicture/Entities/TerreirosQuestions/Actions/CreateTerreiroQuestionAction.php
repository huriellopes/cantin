<?php

namespace App\Archicture\Entities\TerreirosQuestions\Actions;

use App\Archicture\Entities\Terreiros\Models\Terreiro;
use App\Archicture\Entities\TerreirosQuestions\Interfaces\ICreateTerreiroQuestionService;
use App\Archicture\Entities\TerreirosQuestions\Models\TerreiroQuestion;

class CreateTerreiroQuestionAction
{
    /**
     * @param ICreateTerreiroQuestionService $IcreateTerreiroQuestionService
     */
    public function __construct(
        protected ICreateTerreiroQuestionService $IcreateTerreiroQuestionService,
    ){}

    /**
     * @param int $id
     * @param object $params
     * @return TerreiroQuestion
     * @throws \Exception
     */
    public function execute(int $id, object $params) : TerreiroQuestion
    {
        $terreiro = Terreiro::query()->where('id', '=', $id)->first();

        if ($terreiro->id !== $params->terreiro_id) {
            throw new \Exception('Terreiro não encontrado');
        }

        return $this->IcreateTerreiroQuestionService->create($params);
    }
}
