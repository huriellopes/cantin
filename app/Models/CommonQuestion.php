<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CommonQuestion
 * App\Models\CommonQuestion
 * @property $question
 * @property $answer
 * @property $status_id
 */
class CommonQuestion extends GenericModels
{
    protected $table = 'common_questions';

    protected $fillable = [
        'question',
        'answer',
        'status_id'
    ];

    /**
     * @return BelongsTo
     */
    public function status() : BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
