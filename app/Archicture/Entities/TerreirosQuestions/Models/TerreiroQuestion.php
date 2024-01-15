<?php

namespace App\Archicture\Entities\TerreirosQuestions\Models;

use App\Archicture\Entities\Terreiros\Models\Terreiro;
use App\Archicture\Entities\TypePeoples\Models\TypePeople;
use App\Archicture\Generics\Models\GenericModels;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TerreiroQuestion
 * @package App\Archicture\Entities\TerreirosQuestions\Models
 * @property int $id
 * @property int $terreiro_id
 * @property int $type_people_id
 * @property int $number_of_children_of_saint
 * @property int $number_of_children_of_saint_trans
 * @property string $trans_men_and_women
 * @property string $name_gender
 * @property string $fully_welcomes
 * @property string $respect_for_trans_people
 * @property string $suffered_aggregation
 * @property string $inclusion_of_the_name_of_the_land
 * @property int $suggestion_id
 * @property string $suggestion_text
 */
class TerreiroQuestion extends GenericModels
{
protected $table = 'terreiros_questions';

    protected $fillable = [
        'id',
        'terreiro_id',
        'type_people_id',
        'number_of_children_of_saint',
        'number_of_children_of_saint_trans',
        'trans_men_and_women',
        'name_gender',
        'fully_welcomes',
        'respect_for_trans_people',
        'suffered_aggregation',
        'inclusion_of_the_name_of_the_land',
        'suggestion_id',
        'suggestion_text',
    ];

    /**
     * @return HasOne
     */
    public function terreiro() : HasOne
    {
        return $this->hasOne(Terreiro::class);
    }

    /**
     * @return BelongsTo
     */
    public function typePeople() : BelongsTo
    {
        return $this->belongsTo(TypePeople::class);
    }
}
