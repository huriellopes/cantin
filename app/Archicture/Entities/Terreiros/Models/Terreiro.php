<?php

namespace App\Archicture\Entities\Terreiros\Models;

use App\Archicture\Generics\Models\GenericModels;

/**
 * @property $name
 * @property $phone
 * @property $fundationed_at
 * @property $nation_terreiro_id
 * @property $leadership_orunko
 * @property $color_of_leadership
 * @property $type_people_id
 * @property $number_of_children_of_saint
 * @property $number_of_children_of_saint_trans
 * @property $address_id
 * @property $type_terreiro_id
 * @property $trans_men_and_women
 * @property $name_gender
 * @property $fully_welcomes
 * @property $respect_for_trans_people
 * @property $suffered_aggregation
 * @property $inclusion_of_the_name_of_the_land
 * @property $suggestion_id
 * @property $social_media
 */
class Terreiro extends GenericModels
{
    protected $table = "terreiros";

    protected $fillable = [
        'name',
        'phone',
        'fundationed_at',
        'nation_terreiro_id',
        'leadership_orunko',
        'color_of_leadership',
        'type_people_id',
        'number_of_children_of_saint',
        'number_of_children_of_saint_trans',
        'address_id',
        'type_terreiro_id',
        'trans_men_and_women',
        'name_gender',
        'fully_welcomes',
        'respect_for_trans_people',
        'suffered_aggregation',
        'inclusion_of_the_name_of_the_land',
        'suggestion_id',
        'social_media',
    ];
}
