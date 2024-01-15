<?php

namespace App\Archicture\Entities\TerreirosQuestions\Interfaces;

use App\Archicture\Entities\TerreirosQuestions\Models\TerreiroQuestion;

interface ICreateTerreiroQuestionService
{
    /**
     * @param object $params
     * @return TerreiroQuestion
     */
    public function create(object $params) : TerreiroQuestion;
}
