<?php

namespace App\Archicture\Entities\TerreirosQuestions\Validates;

use App\Archicture\Validates\Validate;

class QuestionValidate extends Validate
{
    public array $rules = [
        'terreiro_id' => 'required|integer',
        'type_people_id' => 'required|integer',
        'number_of_children_of_saint' => 'required|integer',
        'number_of_children_of_saint_trans' => 'required|integer',
        'trans_men_and_women' => 'required|string',
        'name_gender' => 'required|string',
        'fully_welcomes' => 'required|string',
        'respect_for_trans_people' => 'required|string',
        'suffered_aggregation' => 'required|string',
        'inclusion_of_the_name_of_the_land' => 'required|string',
        'suggestion_id' => 'nullable|integer',
        'suggestion_text' => 'nullable|string',
    ];
}
