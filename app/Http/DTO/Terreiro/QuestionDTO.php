<?php

namespace App\Http\DTO\Terreiro;

use Spatie\LaravelData\Data;

class QuestionDTO extends Data
{
    /**
     * @param int $terreiro_id
     * @param int $type_people_id
     * @param string $number_of_children_of_saint
     * @param string $number_of_children_of_saint_trans
     * @param string $trans_men_and_women
     * @param string $name_gender
     * @param string $fully_welcomes
     * @param string $respect_for_trans_people
     * @param string $suffered_aggregation
     * @param string $inclusion_of_the_name_of_the_land
     * @param int|null $suggestion_id
     * @param string|null $suggestion_text
     */
    public function __construct(
        public int $terreiro_id,
        public int $type_people_id,
        public string $number_of_children_of_saint,
        public string $number_of_children_of_saint_trans,
        public string $trans_men_and_women,
        public string $name_gender,
        public string $fully_welcomes,
        public string $respect_for_trans_people,
        public string $suffered_aggregation,
        public string $inclusion_of_the_name_of_the_land,
        public int|null $suggestion_id,
        public string|null $suggestion_text,
    ){}
}
