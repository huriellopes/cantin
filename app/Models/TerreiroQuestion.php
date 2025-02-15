<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class TerreiroQuestion extends Model
{
    use SoftDeletes;

    protected $table = 'terreiros_questions';

    /**
     * @var string[]
     */
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
     * @return string[]
     */
    protected function casts() : array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime'
        ];
    }

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
