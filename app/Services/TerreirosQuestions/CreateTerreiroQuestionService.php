<?php

namespace App\Services\TerreirosQuestions;

use App\Models\TerreiroQuestion;

class CreateTerreiroQuestionService
{

    /**
     * @param object $params
     * @return TerreiroQuestion
     */
    public function create(object $params): TerreiroQuestion
    {
        $question = new TerreiroQuestion();
        $question->terreiro_id = $params->terreiro_id;
        $question->type_people_id = $params->type_people_id;
        $question->number_of_children_of_saint = $params->number_of_children_of_saint;
        $question->number_of_children_of_saint_trans = $params->number_of_children_of_saint_trans;
        $question->trans_men_and_women = $params->trans_men_and_women;
        $question->name_gender = $params->name_gender;
        $question->fully_welcomes = $params->fully_welcomes;
        $question->respect_for_trans_people = $params->respect_for_trans_people;
        $question->suffered_aggregation = $params->suffered_aggregation;
        $question->inclusion_of_the_name_of_the_land = $params->inclusion_of_the_name_of_the_land;
        $question->suggestion_id = $params->suggestion_id;
        $question->suggestion_text = $params->suggestion_text;

        $question->save();

        return $question;
    }
}
